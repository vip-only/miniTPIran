<?php

declare(strict_types=1);

require_once __DIR__ . '/common.php';
require_once __DIR__ . '/function_frontOffice.php';

function bo_slugify(string $value): string
{
    $value = trim(mb_strtolower($value));
    $value = preg_replace('/[\x{0300}-\x{036f}]/u', '', iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value) ?: $value) ?? $value;
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? $value;
    $value = trim($value, '-');

    return $value !== '' ? $value : 'article';
}

function bo_set_route_context(string $context): void
{
    $normalized = $context === 'admin' ? 'admin' : 'legacy';
    $GLOBALS['bo_route_context'] = $normalized;
}

function bo_get_route_context(): string
{
    $value = (string) ($GLOBALS['bo_route_context'] ?? 'legacy');
    return $value === 'admin' ? 'admin' : 'legacy';
}

function bo_routes(): array
{
    $isAdmin = bo_get_route_context() === 'admin';

    return [
        'login' => $isAdmin ? '/admin/login' : '/backoffice/login.html',
        'logout' => $isAdmin ? '/admin/logout' : '/backoffice/logout.html',
        'dashboard' => $isAdmin ? '/admin' : '/backoffice.html',
        'article_create' => $isAdmin ? '/admin/articles/create' : '/backoffice/articles/create.html',
        'article_edit_pattern' => $isAdmin ? '/admin/articles/%d/edit' : '/backoffice/articles/edit-%d.html',
        'article_delete_pattern' => $isAdmin ? '/admin/articles/%d/delete' : '/backoffice/articles/delete-%d.html',
    ];
}

function bo_route_url(string $key, ?int $id = null): string
{
    $routes = bo_routes();

    if (!isset($routes[$key])) {
        return '/backoffice.html';
    }

    if ($id !== null && strpos($key, '_pattern') !== false) {
        return sprintf((string) $routes[$key], $id);
    }

    return (string) $routes[$key];
}

function bo_upload_dir(): string
{
    return dirname(__DIR__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'uploads';
}

function bo_is_local_upload_path(?string $path): bool
{
    return strpos(trim((string) $path), '/assets/images/uploads/') === 0;
}

function bo_delete_uploaded_image_if_local(?string $path): void
{
    if (!bo_is_local_upload_path($path)) {
        return;
    }

    $relative = ltrim((string) $path, '/');
    $absolute = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relative);
    if (is_file($absolute)) {
        @unlink($absolute);
    }
}

function bo_process_uploaded_image(?array $file, string &$error): ?string
{
    if (!is_array($file) || (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ((int) ($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        $error = 'Echec upload image.';
        return null;
    }

    $tmp = (string) ($file['tmp_name'] ?? '');
    if ($tmp === '' || !is_uploaded_file($tmp)) {
        $error = 'Fichier image invalide.';
        return null;
    }

    if ((int) ($file['size'] ?? 0) > (5 * 1024 * 1024)) {
        $error = 'Image trop volumineuse (max 5 Mo).';
        return null;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = $finfo ? (string) finfo_file($finfo, $tmp) : '';
    if ($finfo) {
        finfo_close($finfo);
    }

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];

    if (!isset($allowed[$mime])) {
        $error = 'Format image non supporte.';
        return null;
    }

    $dir = bo_upload_dir();
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        $error = 'Creation dossier uploads impossible.';
        return null;
    }

    $name = 'article_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $allowed[$mime];
    $dest = $dir . DIRECTORY_SEPARATOR . $name;

    if (!move_uploaded_file($tmp, $dest)) {
        $error = 'Ecriture image impossible.';
        return null;
    }

    return '/assets/images/uploads/' . $name;
}

function bo_fetch_article_by_id(int $id): ?array
{
    $stmt = app_db()->prepare(
        'SELECT a.id, a.title, a.slug, a.content, a.image_url, a.image_alt, a.status, a.published_at,
                s.meta_title, s.meta_description, s.meta_robots, s.canonical_url
         FROM articles a
         LEFT JOIN seo_metadata s ON s.article_id = a.id
         WHERE a.id = :id
         LIMIT 1'
    );
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    return $row === false ? null : $row;
}

function bo_fetch_articles(): array
{
    $stmt = app_db()->prepare(
        'SELECT a.id, a.title, a.slug, a.content, a.image_url, a.image_alt, a.status, a.published_at,
                s.meta_title, s.meta_description, s.meta_robots, s.canonical_url
         FROM articles a
         LEFT JOIN seo_metadata s ON s.article_id = a.id
         ORDER BY a.published_at DESC, a.id DESC'
    );
    $stmt->execute();
    $rows = $stmt->fetchAll();

    return is_array($rows) ? $rows : [];
}

function bo_require_admin(): bool
{
    if (!empty($_SESSION['is_admin'])) {
        return true;
    }

    http_response_code(401);
    bo_render_login('Session expiree ou acces refuse.');
    return false;
}

function bo_render_login(string $error = '', string $info = ''): void
{
    app_render_page('backoffice/login.php', [
        'error' => $error,
        'info' => $info,
        'formAction' => bo_route_url('login'),
        'routes' => bo_routes(),
    ]);
}

function bo_render_dashboard(string $info = ''): void
{
    $routes = bo_routes();

    app_render_template('backoffice/dashboard', [
        'page' => 'backoffice/dashboard',
        'title' => 'Dashboard Back-Office | Mini Projet Web',
        'metaDescription' => 'Tableau de bord de gestion des articles.',
        'canonicalUrl' => fo_build_canonical((string) ($routes['dashboard'] ?? '/backoffice.html')),
        'ogType' => 'website',
        'articles' => bo_fetch_articles(),
        'info' => $info,
        'baseUrl' => app_base_url(),
        'routes' => $routes,
    ]);
}

function bo_render_article_form(array $article = [], string $mode = 'create', string $error = '', string $info = ''): void
{
    app_render_page('backoffice/article_form.php', [
        'mode' => $mode,
        'error' => $error,
        'info' => $info,
        'article' => $article,
        'formAction' => $mode === 'edit'
            ? bo_route_url('article_edit_pattern', (int) ($article['id'] ?? 0))
            : bo_route_url('article_create'),
        'routes' => bo_routes(),
    ]);
}

function bo_login_get(): void
{
    if (!empty($_SESSION['is_admin'])) {
        bo_render_dashboard();
        return;
    }

    bo_render_login((string) ($_SESSION['auth_error'] ?? ''));
    unset($_SESSION['auth_error']);
}

function bo_login_post(): void
{
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        http_response_code(422);
        bo_render_login('Identifiants invalides.');
        return;
    }

    $stmt = app_db()->prepare('SELECT id, username, password_hash, role FROM users WHERE username = :username LIMIT 1');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, (string) $user['password_hash'])) {
        http_response_code(401);
        bo_render_login('Login ou mot de passe incorrect.');
        return;
    }

    session_regenerate_id(true);
    $_SESSION['is_admin'] = true;
    $_SESSION['admin_id'] = (int) $user['id'];
    $_SESSION['admin_username'] = (string) $user['username'];
    $_SESSION['admin_role'] = (string) ($user['role'] ?? 'admin');

    app_redirect(bo_route_url('dashboard'));
}

function bo_logout_post(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], (bool) $p['secure'], (bool) $p['httponly']);
    }

    session_destroy();
    app_redirect('/');
}

function bo_dashboard_get(): void
{
    if (!bo_require_admin()) {
        return;
    }

    $ok = (string) ($_GET['ok'] ?? '');
    $info = '';
    if ($ok === 'deleted') {
        $info = 'Article supprime.';
    }

    bo_render_dashboard($info);
}

function bo_article_create_get(): void
{
    if (!bo_require_admin()) {
        return;
    }

    bo_render_article_form([], 'create');
}

function bo_article_create_post(): void
{
    if (!bo_require_admin()) {
        return;
    }

    $data = is_array($_POST) ? $_POST : [];
    $title = trim((string) ($data['title'] ?? ''));
    $content = trim((string) ($data['content'] ?? ''));
    $imageAlt = trim((string) ($data['image_alt'] ?? ''));
    $status = in_array((string) ($data['status'] ?? 'draft'), ['draft', 'published'], true) ? (string) $data['status'] : 'draft';
    $metaTitle = trim((string) ($data['meta_title'] ?? ''));
    $metaDescription = trim((string) ($data['meta_description'] ?? ''));
    $metaRobots = trim((string) ($data['meta_robots'] ?? 'index, follow'));
    $canonicalUrl = trim((string) ($data['canonical_url'] ?? ''));

    $uploadError = '';
    $uploadedImagePath = bo_process_uploaded_image($_FILES['image_file'] ?? null, $uploadError);
    if ($uploadError !== '') {
        http_response_code(422);
        bo_render_article_form($data, 'create', $uploadError);
        return;
    }

    if ($title === '' || $content === '') {
        http_response_code(422);
        bo_render_article_form($data, 'create', 'Le titre et le contenu sont obligatoires.');
        return;
    }

    $slug = bo_slugify($title);
    $publishedAt = app_parse_datetime($data['published_at'] ?? null);

    $db = app_db();
    $db->beginTransaction();

    try {
        $stmt = $db->prepare('INSERT INTO articles (title, slug, content, image_url, image_alt, status, published_at) VALUES (:title, :slug, :content, :image_url, :image_alt, :status, :published_at)');
        $stmt->execute([
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'image_url' => $uploadedImagePath,
            'image_alt' => $imageAlt,
            'status' => $status,
            'published_at' => $publishedAt,
        ]);

        $articleId = (int) $db->lastInsertId();
        if ($canonicalUrl === '') {
            $canonicalUrl = '/article/' . $slug;
        }

        $stmtSeo = $db->prepare('INSERT INTO seo_metadata (article_id, meta_title, meta_description, meta_robots, canonical_url) VALUES (:article_id, :meta_title, :meta_description, :meta_robots, :canonical_url)');
        $stmtSeo->execute([
            'article_id' => $articleId,
            'meta_title' => $metaTitle !== '' ? $metaTitle : $title,
            'meta_description' => $metaDescription,
            'meta_robots' => $metaRobots !== '' ? $metaRobots : 'index, follow',
            'canonical_url' => $canonicalUrl,
        ]);

        $db->commit();
        app_redirect(bo_route_url('article_edit_pattern', $articleId) . '?ok=created');
    } catch (Throwable $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }

        http_response_code(500);
        bo_render_article_form($data, 'create', 'Erreur lors de la creation de l article.');
    }
}

function bo_article_edit_get(int $id): void
{
    if (!bo_require_admin()) {
        return;
    }

    $article = bo_fetch_article_by_id($id);
    if ($article === null) {
        http_response_code(404);
        bo_render_dashboard('Article introuvable.');
        return;
    }

    $ok = (string) ($_GET['ok'] ?? '');
    $info = '';
    if ($ok === 'created') {
        $info = 'Article cree avec succes.';
    } elseif ($ok === 'updated') {
        $info = 'Article mis a jour avec succes.';
    }

    bo_render_article_form($article, 'edit', '', $info);
}

function bo_article_edit_post(int $id): void
{
    if (!bo_require_admin()) {
        return;
    }

    $existing = bo_fetch_article_by_id($id);
    if ($existing === null) {
        http_response_code(404);
        bo_render_dashboard('Article introuvable.');
        return;
    }

    $data = is_array($_POST) ? $_POST : [];
    $title = trim((string) ($data['title'] ?? ''));
    $content = trim((string) ($data['content'] ?? ''));
    $imageAlt = trim((string) ($data['image_alt'] ?? ''));
    $status = in_array((string) ($data['status'] ?? 'draft'), ['draft', 'published'], true) ? (string) $data['status'] : 'draft';
    $metaTitle = trim((string) ($data['meta_title'] ?? ''));
    $metaDescription = trim((string) ($data['meta_description'] ?? ''));
    $metaRobots = trim((string) ($data['meta_robots'] ?? 'index, follow'));
    $canonicalUrl = trim((string) ($data['canonical_url'] ?? ''));

    $uploadError = '';
    $uploadedImagePath = bo_process_uploaded_image($_FILES['image_file'] ?? null, $uploadError);
    if ($uploadError !== '') {
        http_response_code(422);
        $data['id'] = $id;
        $data['image_url'] = (string) ($existing['image_url'] ?? '');
        bo_render_article_form($data, 'edit', $uploadError);
        return;
    }

    if ($title === '' || $content === '') {
        http_response_code(422);
        $data['id'] = $id;
        bo_render_article_form($data, 'edit', 'Le titre et le contenu sont obligatoires.');
        return;
    }

    $finalImage = $uploadedImagePath !== null ? $uploadedImagePath : (($existing['image_url'] ?? '') !== '' ? (string) $existing['image_url'] : null);
    $slug = bo_slugify($title);
    $publishedAt = app_parse_datetime($data['published_at'] ?? null);

    $db = app_db();
    $db->beginTransaction();

    try {
        $stmt = $db->prepare('UPDATE articles SET title = :title, slug = :slug, content = :content, image_url = :image_url, image_alt = :image_alt, status = :status, published_at = :published_at WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'image_url' => $finalImage,
            'image_alt' => $imageAlt,
            'status' => $status,
            'published_at' => $publishedAt,
        ]);

        if ($uploadedImagePath !== null && !empty($existing['image_url']) && (string) $existing['image_url'] !== $uploadedImagePath) {
            bo_delete_uploaded_image_if_local((string) $existing['image_url']);
        }

        if ($canonicalUrl === '') {
            $canonicalUrl = '/article/' . $slug;
        }

        $stmtSeo = $db->prepare('INSERT INTO seo_metadata (article_id, meta_title, meta_description, meta_robots, canonical_url) VALUES (:article_id, :meta_title, :meta_description, :meta_robots, :canonical_url) ON DUPLICATE KEY UPDATE meta_title = VALUES(meta_title), meta_description = VALUES(meta_description), meta_robots = VALUES(meta_robots), canonical_url = VALUES(canonical_url)');
        $stmtSeo->execute([
            'article_id' => $id,
            'meta_title' => $metaTitle !== '' ? $metaTitle : $title,
            'meta_description' => $metaDescription,
            'meta_robots' => $metaRobots !== '' ? $metaRobots : 'index, follow',
            'canonical_url' => $canonicalUrl,
        ]);

        $db->commit();
        app_redirect(bo_route_url('article_edit_pattern', $id) . '?ok=updated');
    } catch (Throwable $e) {
        if ($db->inTransaction()) {
            $db->rollBack();
        }

        http_response_code(500);
        $data['id'] = $id;
        bo_render_article_form($data, 'edit', 'Erreur lors de la mise a jour de l article.');
    }
}

function bo_article_delete_post(int $id): void
{
    if (!bo_require_admin()) {
        return;
    }

    $existing = bo_fetch_article_by_id($id);

    $stmt = app_db()->prepare('DELETE FROM articles WHERE id = :id');
    $stmt->execute(['id' => $id]);

    if ($existing !== null) {
        bo_delete_uploaded_image_if_local((string) ($existing['image_url'] ?? ''));
    }

    app_redirect(bo_route_url('dashboard') . '?ok=deleted');
}
