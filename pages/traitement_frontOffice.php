<?php

declare(strict_types=1);

function handle_frontoffice_request(string $method, string $path): bool
{
    if ($method !== 'GET') {
        return false;
    }

    $fo = (string) ($_GET['fo'] ?? '');

    if ($fo === 'home' || $path === '/') {
        fo_render_home();
        return true;
    }

    if ($fo === 'article_slug') {
        $slug = trim((string) ($_GET['slug'] ?? ''));
        if ($slug !== '' && preg_match('#^[a-z0-9\-]+$#', $slug)) {
            fo_render_article_slug($slug);
            return true;
        }

        fo_render_not_found('/article/' . $slug);
        return true;
    }

    if ($fo === 'article_legacy') {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $rubrique = isset($_GET['rubrique']) ? (int) $_GET['rubrique'] : 0;

        if ($id > 0 && $page > 0 && $rubrique >= 0) {
            fo_render_article_legacy($id, $page, $rubrique);
            return true;
        }

        fo_render_not_found('/articles/article.php');
        return true;
    }

    if ($fo === 'article_pretty') {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $rubrique = isset($_GET['rubrique']) ? (int) $_GET['rubrique'] : 3210;

        if ($id > 0) {
            fo_render_article_pretty($id, $rubrique);
            return true;
        }

        fo_render_not_found('/international/article');
        return true;
    }

    return false;
}
