<?php

namespace app\controllers;

use Flight;
use PDO;

class FrontOfficeController
{
	private function renderArticlePage(array $article, string $canonicalPath, string $currentPath): void
	{
		$imageAlt = trim((string) ($article['image_alt'] ?? ''));
		if ($imageAlt === '') {
			$imageAlt = 'Illustration: ' . $article['title'];
		}

		$article['image_alt'] = $imageAlt;
		$article['content'] = $this->normalizeArticleHtml((string) ($article['content'] ?? ''), $imageAlt);
		$metaTitle = trim((string) ($article['meta_title'] ?? ''));
		$metaDescription = trim((string) ($article['meta_description'] ?? ''));
		$canonicalUrl = trim((string) ($article['canonical_url'] ?? ''));

		Flight::render('template', [
			'page' => 'article',
			'article' => $article,
			'title' => $metaTitle !== '' ? $metaTitle : $article['title'] . ' | Mini Projet Web',
			'metaDescription' => $metaDescription !== '' ? mb_substr($metaDescription, 0, 155) : $this->fallbackDescription((string) $article['title'], (string) $article['content']),
			'canonicalUrl' => $canonicalUrl !== '' ? $canonicalUrl : $this->buildCanonical($canonicalPath),
			'ogType' => 'article',
			'currentPath' => $currentPath
		]);
	}

	private function buildCanonical(string $path = ''): string
	{
		$base = rtrim((string) Flight::get('flight.base_url'), '/');
		$cleanPath = ltrim($path, '/');
		return $cleanPath === '' ? $base . '/' : $base . '/' . $cleanPath;
	}

	private function fallbackDescription(string $title, string $content = ''): string
	{
		if (trim($content) !== '') {
			$excerpt = trim(preg_replace('/\s+/', ' ', strip_tags($content)) ?? '');
			if ($excerpt !== '') {
				return mb_substr($excerpt, 0, 155);
			}
		}

		return mb_substr('Lecture de l\'article: ' . $title, 0, 155);
	}

	private function normalizeArticleHtml(string $html, string $fallbackAlt): string
	{
		if (trim($html) === '' || class_exists('DOMDocument') === false) {
			return $html;
		}

		$dom = new \DOMDocument();
		libxml_use_internal_errors(true);
		$wrappedHtml = '<!DOCTYPE html><html><body>' . $html . '</body></html>';
		$dom->loadHTML(mb_convert_encoding($wrappedHtml, 'HTML-ENTITIES', 'UTF-8'));

		foreach (['h1', 'h4', 'h5', 'h6'] as $tag) {
			$nodes = $dom->getElementsByTagName($tag);
			for ($i = $nodes->length - 1; $i >= 0; $i--) {
				$node = $nodes->item($i);
				if ($node === null) {
					continue;
				}

				$replacementTag = $tag === 'h1' ? 'h2' : 'h3';
				$replacement = $dom->createElement($replacementTag, $node->textContent ?? '');
				if ($node->parentNode !== null) {
					$node->parentNode->replaceChild($replacement, $node);
				}
			}
		}

		$images = $dom->getElementsByTagName('img');
		for ($i = 0; $i < $images->length; $i++) {
			$img = $images->item($i);
			if ($img === null) {
				continue;
			}

			$currentAlt = trim((string) $img->getAttribute('alt'));
			if ($currentAlt === '') {
				$img->setAttribute('alt', $fallbackAlt);
			}
		}

		$body = $dom->getElementsByTagName('body')->item(0);
		$result = '';
		if ($body !== null) {
			foreach ($body->childNodes as $child) {
				$result .= $dom->saveHTML($child);
			}
		}

		libxml_clear_errors();
		return $result !== '' ? $result : $html;
	}

	public function home(): void
	{
		$db = Flight::db();
		$stmt = $db->prepare("SELECT id, title, content, image_url, image_alt, published_at FROM articles WHERE status = 'published' ORDER BY published_at DESC");
		$stmt->execute();
		$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

		Flight::render('template', [
			'page' => 'home',
			'articles' => $articles,
			'title' => 'Accueil | Mini Projet Web',
			'metaDescription' => 'Actualites et analyses sur la guerre en Iran.',
			'canonicalUrl' => $this->buildCanonical('/'),
			'ogType' => 'website',
			'currentPath' => '/'
		]);
	}

	public function articleLegacy(int $id, int $page, int $rubrique): void
	{
		$db = Flight::db();
		$stmt = $db->prepare(
			"SELECT a.id, a.title, a.content, a.image_url, a.image_alt, a.published_at,
					s.meta_title, s.meta_description, s.canonical_url
			 FROM articles a
			 LEFT JOIN seo_metadata s ON s.article_id = a.id
			 WHERE a.id = :id AND a.status = 'published'
			 LIMIT 1"
		);
		$stmt->execute(['id' => $id]);
		$article = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($article === false) {
			http_response_code(404);
			Flight::render('template', [
				'page' => '404',
				'title' => '404 | Article introuvable',
				'metaDescription' => 'La page demandee est introuvable.',
				'canonicalUrl' => $this->buildCanonical('articles/article-' . (int) $id . '-' . (int) $page . '-' . (int) $rubrique . '.html'),
				'ogType' => 'website',
				'currentPath' => '/articles/article-' . (int) $id . '-' . (int) $page . '-' . (int) $rubrique . '.html'
			]);
			return;
		}

		$this->renderArticlePage(
			$article,
			'articles/article-' . (int) $id . '-' . (int) $page . '-' . (int) $rubrique . '.html',
			'/articles/article-' . (int) $id . '-' . (int) $page . '-' . (int) $rubrique . '.html'
		);
	}

	public function articleLegacyRoute(string $legacy): void
	{
		if (!preg_match('/^article-(\d+)-(\d+)-(\d+)\.html$/', $legacy, $matches)) {
			$this->notFound();
			return;
		}

		$this->articleLegacy((int) $matches[1], (int) $matches[2], (int) $matches[3]);
	}

	public function articleLegacyQuery(): void
	{
		$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
		$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
		$rubrique = isset($_GET['rubrique']) ? (int) $_GET['rubrique'] : 0;

		if ($id <= 0 || $page <= 0 || $rubrique < 0) {
			$this->notFound();
			return;
		}

		$this->articleLegacy($id, $page, $rubrique);
	}

	public function notFound(): void
	{
		http_response_code(404);
		Flight::render('template', [
			'page' => '404',
			'title' => '404 | Page introuvable',
			'metaDescription' => 'La page demandee est introuvable.',
			'canonicalUrl' => $this->buildCanonical('/'),
			'ogType' => 'website',
			'currentPath' => '/404'
		]);
	}

	public function adminLoginGet(): void
	{
		echo '<h1>Admin Login (GET)</h1>';
	}

	public function adminLoginPost(): void
	{
		echo '<h1>Admin Login (POST)</h1>';
	}

	public function adminDashboard(): void
	{
		echo '<h1>Admin Dashboard</h1>';
	}

	public function adminArticleCreateGet(): void
	{
		echo '<h1>Admin Article Create (GET)</h1>';
	}

	public function adminArticleCreatePost(): void
	{
		echo '<h1>Admin Article Create (POST)</h1>';
	}

	public function adminArticleEditGet(int $id): void
	{
		echo '<h1>Admin Article Edit (GET)</h1><p>ID: ' . (int) $id . '</p>';
	}

	public function adminArticleEditPost(int $id): void
	{
		echo '<h1>Admin Article Edit (POST)</h1><p>ID: ' . (int) $id . '</p>';
	}

	public function adminArticleDeletePost(int $id): void
	{
		echo '<h1>Admin Article Delete (POST)</h1><p>ID: ' . (int) $id . '</p>';
	}
}
