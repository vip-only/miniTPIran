<?php

declare(strict_types=1);

function handle_backoffice_request(string $method, string $path): bool
{
    $action = (string) ($_GET['bo'] ?? '');
    if ($action === '') {
        return false;
    }

    $context = ((string) ($_GET['ctx'] ?? 'legacy')) === 'admin' ? 'admin' : 'legacy';
    bo_set_route_context($context);

    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    if ($action === 'login') {
        if ($method === 'POST') {
            bo_login_post();
        } else {
            bo_login_get();
        }

        return true;
    }

    if ($action === 'logout') {
        if ($method === 'POST') {
            bo_logout_post();
            return true;
        }

        return false;
    }

    if ($action === 'dashboard' && $method === 'GET') {
        bo_dashboard_get();
        return true;
    }

    if ($action === 'article_create') {
        if ($method === 'POST') {
            bo_article_create_post();
        } else {
            bo_article_create_get();
        }

        return true;
    }

    if ($action === 'article_edit' && $id > 0) {
        if ($method === 'POST') {
            bo_article_edit_post($id);
        } else {
            bo_article_edit_get($id);
        }

        return true;
    }

    if ($action === 'article_delete' && $id > 0 && $method === 'POST') {
        bo_article_delete_post($id);
        return true;
    }

    return false;
}
