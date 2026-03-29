<?php
// filepath: f:\Study\S6\Rojo\miniTPIran\app\controllers\BackOfficeController.php

namespace app\controllers;

use Flight;
use PDO;

class BackOfficeController
{
    private function requestData(): array
    {
        $request = Flight::request();
        $data = $request->data->getData();

        return is_array($data) ? $data : [];
    }

    private function slugify(string $value): string
    {
        $value = trim(mb_strtolower($value));
        $value = preg_replace('/[\x{0300}-\x{036f}]/u', '', iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) ?: $value) ?? $value;
        $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? $value;
        $value = trim($value, '-');

        return $value !== '' ? $value : 'article';
    }

    private function normalizePublishedAt(?string $value): ?string
    {
        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }

    private function fetchArticleById(int $id): ?array
    {
        $stmt = Flight::db()->prepare(
            'SELECT a.id, a.title, a.slug, a.content, a.image_url, a.image_alt, a.status, a.published_at,
                    s.meta_title, s.meta_description, s.meta_robots, s.canonical_url
             FROM articles a
             LEFT JOIN seo_metadata s ON s.article_id = a.id
             WHERE a.id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        return $article === false ? null : $article;
    }

    private function fetchArticles(): array
    {
        $stmt = Flight::db()->prepare(
            'SELECT a.id, a.title, a.slug, a.content, a.image_url, a.image_alt, a.status, a.published_at,
                    s.meta_title, s.meta_description, s.meta_robots, s.canonical_url
             FROM articles a
             LEFT JOIN seo_metadata s ON s.article_id = a.id
             ORDER BY a.published_at DESC, a.id DESC'
        );
        $stmt->execute();

        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return is_array($articles) ? $articles : [];
    }

    private function renderArticleForm(array $article = [], string $mode = 'create', string $error = '', string $info = ''): void
    {
        Flight::render('backoffice/article_form', [
            'mode' => $mode,
            'error' => $error,
            'info' => $info,
            'article' => $article,
            'formAction' => $mode === 'edit'
                ? '/backoffice/articles/edit-' . (int) ($article['id'] ?? 0) . '.html'
                : '/backoffice/articles/create.html',
        ]);
    }

    private function renderLogin(string $error = '', string $info = ''): void
    {
        Flight::render('backoffice/login', [
            'error' => $error,
            'info' => $info,
            'formAction' => '/backoffice/login.html',
        ]);
    }

    private function renderDashboard(string $info = ''): void
    {
        Flight::render('template', [
            'page' => 'backoffice/dashboard',
            'title' => 'Dashboard Back-Office | Mini Projet Web',
            'metaDescription' => 'Tableau de bord de gestion des articles.',
            'canonicalUrl' => '/backoffice.html',
            'ogType' => 'website',
            'username' => (string) ($_SESSION['admin_username'] ?? 'admin'),
            'logoutAction' => '/backoffice/logout.html',
            'createAction' => '/backoffice/articles/create.html',
            'articles' => $this->fetchArticles(),
            'info' => $info,
        ]);
    }

    private function requireAdmin(): bool
    {
        if (empty($_SESSION['is_admin'])) {
            http_response_code(401);
            $this->renderLogin('Session expirée ou accès refusé.');
            return false;
        }

        return true;
    }

    public function adminLoginGet(): void
    {
        if (!empty($_SESSION['is_admin'])) {
            $this->renderDashboard();
            return;
        }

        $error = (string) ($_SESSION['auth_error'] ?? '');
        unset($_SESSION['auth_error']);

        $this->renderLogin($error);
    }

    public function adminLoginPost(): void
    {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($username === '' || $password === '') {
            http_response_code(422);
            $this->renderLogin('Identifiants invalides.');
            return;
        }

        $db = Flight::db();
        $stmt = $db->prepare('SELECT id, username, password_hash, role FROM users WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, (string) $user['password_hash'])) {
            http_response_code(401);
            $this->renderLogin('Login ou mot de passe incorrect.');
            return;
        }

        session_regenerate_id(true);
        $_SESSION['is_admin'] = true;
        $_SESSION['admin_id'] = (int) $user['id'];
        $_SESSION['admin_username'] = (string) $user['username'];
        $_SESSION['admin_role'] = (string) ($user['role'] ?? 'admin');

        $this->renderDashboard('Connexion réussie.');
    }

    public function adminLogoutPost(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $p['path'],
                $p['domain'],
                (bool) $p['secure'],
                (bool) $p['httponly']
            );
        }

        session_destroy();
        $this->renderLogin('', 'Déconnexion réussie.');
    }

    public function adminDashboard(): void
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $this->renderDashboard();
    }

    public function adminArticleCreateGet(): void
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $this->renderArticleForm([], 'create');
    }

    public function adminArticleCreatePost(): void
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $data = $this->requestData();
        $title = trim((string) ($data['title'] ?? ''));
        $content = trim((string) ($data['content'] ?? ''));
        $imageUrl = trim((string) ($data['image_url'] ?? ''));
        $imageAlt = trim((string) ($data['image_alt'] ?? ''));
        $status = in_array(($data['status'] ?? 'draft'), ['draft', 'published'], true) ? (string) $data['status'] : 'draft';
        $metaTitle = trim((string) ($data['meta_title'] ?? ''));
        $metaDescription = trim((string) ($data['meta_description'] ?? ''));
        $metaRobots = trim((string) ($data['meta_robots'] ?? 'index, follow'));
        $canonicalUrl = trim((string) ($data['canonical_url'] ?? ''));

        if ($title === '' || $content === '') {
            http_response_code(422);
            $this->renderArticleForm($data, 'create', 'Le titre et le contenu sont obligatoires.');
            return;
        }

        $slug = $this->slugify($title);
        $publishedAt = $this->normalizePublishedAt($data['published_at'] ?? null);

        $db = Flight::db();
        $db->beginTransaction();

        try {
            $stmt = $db->prepare(
                'INSERT INTO articles (title, slug, content, image_url, image_alt, status, published_at)
                 VALUES (:title, :slug, :content, :image_url, :image_alt, :status, :published_at)'
            );
            $stmt->execute([
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'image_url' => $imageUrl !== '' ? $imageUrl : null,
                'image_alt' => $imageAlt,
                'status' => $status,
                'published_at' => $publishedAt,
            ]);

            $articleId = (int) $db->lastInsertId();
            if ($canonicalUrl === '') {
                $canonicalUrl = '/article/' . $slug;
            }

            $stmtSeo = $db->prepare(
                'INSERT INTO seo_metadata (article_id, meta_title, meta_description, meta_robots, canonical_url)
                 VALUES (:article_id, :meta_title, :meta_description, :meta_robots, :canonical_url)'
            );
            $stmtSeo->execute([
                'article_id' => $articleId,
                'meta_title' => $metaTitle !== '' ? $metaTitle : $title,
                'meta_description' => $metaDescription,
                'meta_robots' => $metaRobots !== '' ? $metaRobots : 'index, follow',
                'canonical_url' => $canonicalUrl,
            ]);

            $db->commit();
            $this->renderArticleForm($this->fetchArticleById($articleId) ?? [], 'edit', '', 'Article créé avec succès.');
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            http_response_code(500);
            $this->renderArticleForm($data, 'create', 'Erreur lors de la création de l’article.');
        }
    }

    public function adminArticleEditGet(string|int $id): void
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $articleId = (int) $id;
        $article = $this->fetchArticleById($articleId);
        if ($article === null) {
            http_response_code(404);
            $this->renderDashboard('Article introuvable.');
            return;
        }

        $this->renderArticleForm($article, 'edit');
    }

    public function adminArticleEditPost(string|int $id): void
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $articleId = (int) $id;
        $existing = $this->fetchArticleById($articleId);
        if ($existing === null) {
            http_response_code(404);
            $this->renderDashboard('Article introuvable.');
            return;
        }

        $data = $this->requestData();
        $title = trim((string) ($data['title'] ?? ''));
        $content = trim((string) ($data['content'] ?? ''));
        $imageUrl = trim((string) ($data['image_url'] ?? ''));
        $imageAlt = trim((string) ($data['image_alt'] ?? ''));
        $status = in_array(($data['status'] ?? 'draft'), ['draft', 'published'], true) ? (string) $data['status'] : 'draft';
        $metaTitle = trim((string) ($data['meta_title'] ?? ''));
        $metaDescription = trim((string) ($data['meta_description'] ?? ''));
        $metaRobots = trim((string) ($data['meta_robots'] ?? 'index, follow'));
        $canonicalUrl = trim((string) ($data['canonical_url'] ?? ''));

        if ($title === '' || $content === '') {
            http_response_code(422);
            $data['id'] = $id;
            $this->renderArticleForm($data, 'edit', 'Le titre et le contenu sont obligatoires.');
            return;
        }

        $slug = $this->slugify($title);
        $publishedAt = $this->normalizePublishedAt($data['published_at'] ?? null);

        $db = Flight::db();
        $db->beginTransaction();

        try {
            $stmt = $db->prepare(
                'UPDATE articles
                 SET title = :title,
                     slug = :slug,
                     content = :content,
                     image_url = :image_url,
                     image_alt = :image_alt,
                     status = :status,
                     published_at = :published_at
                 WHERE id = :id'
            );
            $stmt->execute([
                'id' => $articleId,
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'image_url' => $imageUrl !== '' ? $imageUrl : null,
                'image_alt' => $imageAlt,
                'status' => $status,
                'published_at' => $publishedAt,
            ]);

            if ($canonicalUrl === '') {
                $canonicalUrl = '/article/' . $slug;
            }

            $stmtSeo = $db->prepare(
                'INSERT INTO seo_metadata (article_id, meta_title, meta_description, meta_robots, canonical_url)
                 VALUES (:article_id, :meta_title, :meta_description, :meta_robots, :canonical_url)
                 ON DUPLICATE KEY UPDATE
                     meta_title = VALUES(meta_title),
                     meta_description = VALUES(meta_description),
                     meta_robots = VALUES(meta_robots),
                     canonical_url = VALUES(canonical_url)'
            );
            $stmtSeo->execute([
                'article_id' => $articleId,
                'meta_title' => $metaTitle !== '' ? $metaTitle : $title,
                'meta_description' => $metaDescription,
                'meta_robots' => $metaRobots !== '' ? $metaRobots : 'index, follow',
                'canonical_url' => $canonicalUrl,
            ]);

            $db->commit();
            $this->renderArticleForm($this->fetchArticleById($articleId) ?? [], 'edit', '', 'Article mis à jour avec succès.');
        } catch (\Throwable $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }

            http_response_code(500);
            $data['id'] = $articleId;
            $this->renderArticleForm($data, 'edit', 'Erreur lors de la mise à jour de l’article.');
        }
    }

    public function adminArticleDeletePost(string|int $id): void
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $stmt = Flight::db()->prepare('DELETE FROM articles WHERE id = :id');
        $stmt->execute(['id' => (int) $id]);

        $this->renderDashboard('Article supprimé.');
    }
}