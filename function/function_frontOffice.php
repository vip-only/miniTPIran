<?php

declare(strict_types=1);

require_once __DIR__ . '/common.php';

function fo_build_canonical(string $path = ''): string
{
    $base = rtrim(app_base_url(), '/');
    $cleanPath = ltrim($path, '/');
    return $cleanPath === '' ? $base . '/' : $base . '/' . $cleanPath;
}

function fo_home_pretty_path(): string
{
    return '/international/actualites-guerre-iran.html';
}

function fo_slugify(string $value): string
{
    $value = trim(mb_strtolower($value));
    $value = preg_replace('/[\x{0300}-\x{036f}]/u', '', iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) ?: $value) ?? $value;
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? $value;
    $value = trim($value, '-');

    return $value !== '' ? $value : 'article';
}

function fo_article_pretty_url(array $article, int $rubrique = 3210): string
{
    $id = (int) ($article['id'] ?? 0);
    $slug = trim((string) ($article['slug'] ?? ''));
    if ($slug === '') {
        $slug = fo_slugify((string) ($article['title'] ?? 'article'));
    }

    $publishedAt = trim((string) ($article['published_at'] ?? ''));
    $ts = $publishedAt !== '' ? strtotime($publishedAt) : false;
    if ($ts === false) {
        $ts = time();
    }

    $yyyy = date('Y', $ts);
    $mm = date('m', $ts);
    $dd = date('d', $ts);

    return '/international/article/' . $yyyy . '/' . $mm . '/' . $dd . '/' . $slug . '_' . $id . '_' . $rubrique . '.html';
}

function fo_fallback_description(string $title, string $content = ''): string
{
    $text = trim(preg_replace('/\s+/', ' ', strip_tags($content)) ?? '');
    if ($text !== '') {
        return mb_substr($text, 0, 155);
    }

    return mb_substr('Lecture de l article: ' . $title, 0, 155);
}

function fo_normalize_article_html(string $html, string $fallbackAlt): string
{
    if (trim($html) === '' || class_exists('DOMDocument') === false) {
        return $html;
    }

    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $wrappedHtml = '<!DOCTYPE html><html><body>' . $html . '</body></html>';
    $dom->loadHTML($wrappedHtml);

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

        if (trim((string) $img->getAttribute('alt')) === '') {
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

function fo_get_home_articles(): array
{
    $stmt = app_db()->prepare("SELECT id, title, slug, content, image_url, image_alt, published_at FROM articles WHERE status = 'published' ORDER BY published_at DESC, id DESC");
    $stmt->execute();
    $rows = $stmt->fetchAll();

    return is_array($rows) ? $rows : [];
}

function fo_get_article_by_id(int $id): ?array
{
    $stmt = app_db()->prepare(
    "SELECT a.id, a.title, a.slug, a.content, a.image_url, a.image_alt, a.published_at,
                s.meta_title, s.meta_description, s.canonical_url
         FROM articles a
         LEFT JOIN seo_metadata s ON s.article_id = a.id
         WHERE a.id = :id AND a.status = 'published'
         LIMIT 1"
    );
    $stmt->execute(['id' => $id]);
    $article = $stmt->fetch();

    return $article === false ? null : $article;
}

function fo_get_article_by_slug(string $slug): ?array
{
    $stmt = app_db()->prepare(
        "SELECT a.id, a.title, a.slug, a.content, a.image_url, a.image_alt, a.published_at,
                s.meta_title, s.meta_description, s.canonical_url
         FROM articles a
         LEFT JOIN seo_metadata s ON s.article_id = a.id
         WHERE a.slug = :slug AND a.status = 'published'
         LIMIT 1"
    );
    $stmt->execute(['slug' => $slug]);
    $article = $stmt->fetch();

    return $article === false ? null : $article;
}

function fo_render_home(string $info = ''): void
{
    $articles = fo_get_home_articles();

    app_render_template('home', [
        'page' => 'home',
        'title' => 'Accueil | Mini Projet Web',
        'metaDescription' => 'Actualites et analyses sur la guerre en Iran.',
        'canonicalUrl' => fo_build_canonical(ltrim(fo_home_pretty_path(), '/')),
        'ogType' => 'website',
        'articles' => $articles,
        'baseUrl' => app_base_url(),
        'flashMessage' => $info,
    ]);
}

function fo_render_not_found(string $path = '/'): void
{
    http_response_code(404);

    app_render_template('404', [
        'page' => '404',
        'title' => '404 | Page introuvable',
        'metaDescription' => 'La page demandee est introuvable.',
        'canonicalUrl' => fo_build_canonical($path),
        'ogType' => 'website',
        'baseUrl' => app_base_url(),
    ]);
}

function fo_render_article_legacy(int $id, int $page, int $rubrique): void
{
    $article = fo_get_article_by_id($id);
    if ($article === null) {
        fo_render_not_found('/articles/article-' . $id . '-' . $page . '-' . $rubrique . '.html');
        return;
    }

    $imageAlt = trim((string) ($article['image_alt'] ?? ''));
    if ($imageAlt === '') {
        $imageAlt = 'Illustration: ' . (string) $article['title'];
    }

    $article['image_alt'] = $imageAlt;
    $article['content'] = fo_normalize_article_html((string) ($article['content'] ?? ''), $imageAlt);

    $metaTitle = trim((string) ($article['meta_title'] ?? ''));
    $metaDescription = trim((string) ($article['meta_description'] ?? ''));
    $canonical = trim((string) ($article['canonical_url'] ?? ''));

    app_render_template('article', [
        'page' => 'article',
        'article' => $article,
        'title' => $metaTitle !== '' ? $metaTitle : ((string) $article['title'] . ' | Mini Projet Web'),
        'metaDescription' => $metaDescription !== '' ? mb_substr($metaDescription, 0, 155) : fo_fallback_description((string) $article['title'], (string) $article['content']),
        'canonicalUrl' => $canonical !== '' ? $canonical : fo_build_canonical(ltrim(fo_article_pretty_url($article, $rubrique), '/')),
        'ogType' => 'article',
        'baseUrl' => app_base_url(),
    ]);
}

function fo_render_article_pretty(int $id, int $rubrique = 3210): void
{
    fo_render_article_legacy($id, 1, $rubrique);
}

function fo_render_article_slug(string $slug): void
{
    $article = fo_get_article_by_slug($slug);
    if ($article === null) {
        fo_render_not_found('/article/' . $slug);
        return;
    }

    $imageAlt = trim((string) ($article['image_alt'] ?? ''));
    if ($imageAlt === '') {
        $imageAlt = 'Illustration: ' . (string) $article['title'];
    }

    $article['image_alt'] = $imageAlt;
    $article['content'] = fo_normalize_article_html((string) ($article['content'] ?? ''), $imageAlt);

    $metaTitle = trim((string) ($article['meta_title'] ?? ''));
    $metaDescription = trim((string) ($article['meta_description'] ?? ''));
    $canonical = trim((string) ($article['canonical_url'] ?? ''));

    app_render_template('article', [
        'page' => 'article',
        'article' => $article,
        'title' => $metaTitle !== '' ? $metaTitle : ((string) $article['title'] . ' | Mini Projet Web'),
        'metaDescription' => $metaDescription !== '' ? mb_substr($metaDescription, 0, 155) : fo_fallback_description((string) $article['title'], (string) $article['content']),
        'canonicalUrl' => $canonical !== '' ? $canonical : fo_build_canonical('article/' . $slug),
        'ogType' => 'article',
        'baseUrl' => app_base_url(),
    ]);
}
