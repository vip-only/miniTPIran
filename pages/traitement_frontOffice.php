<?php

declare(strict_types=1);

function handle_frontoffice_request(string $method, string $path): bool
{
    $routes = [
        ['GET', '#^/$#', static function (array $m): void {
            fo_render_home();
        }],
        ['GET', '#^/article/([a-z0-9\-]+)$#', static function (array $m): void {
            fo_render_article_slug((string) $m[1]);
        }],
        ['GET', '#^/articles/article-(\d+)-(\d+)-(\d+)\.html$#', static function (array $m): void {
            fo_render_article_legacy((int) $m[1], (int) $m[2], (int) $m[3]);
        }],
        ['GET', '#^/articles/article\.php$#', static function (array $m): void {
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            $rubrique = isset($_GET['rubrique']) ? (int) $_GET['rubrique'] : 0;

            if ($id > 0 && $page > 0 && $rubrique >= 0) {
                fo_render_article_legacy($id, $page, $rubrique);
                return;
            }

            fo_render_not_found('/articles/article.php');
        }],
    ];

    foreach ($routes as [$routeMethod, $pattern, $handler]) {
        if ($method !== $routeMethod) {
            continue;
        }

        if (!preg_match($pattern, $path, $matches)) {
            continue;
        }

        $handler($matches);
        return true;
    }

    return false;
}
