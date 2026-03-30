<?php

declare(strict_types=1);

function handle_backoffice_request(string $method, string $path): bool
{
    $routes = [
        ['GET', '#^/admin/login$#', 'admin', static function (array $m): void {
            bo_login_get();
        }],
        ['POST', '#^/admin/login$#', 'admin', static function (array $m): void {
            bo_login_post();
        }],
        ['POST', '#^/admin/logout$#', 'admin', static function (array $m): void {
            bo_logout_post();
        }],
        ['GET', '#^/admin$#', 'admin', static function (array $m): void {
            bo_dashboard_get();
        }],
        ['GET', '#^/admin/articles/create$#', 'admin', static function (array $m): void {
            bo_article_create_get();
        }],
        ['POST', '#^/admin/articles/create$#', 'admin', static function (array $m): void {
            bo_article_create_post();
        }],
        ['GET', '#^/admin/articles/(\d+)/edit$#', 'admin', static function (array $m): void {
            bo_article_edit_get((int) $m[1]);
        }],
        ['POST', '#^/admin/articles/(\d+)/edit$#', 'admin', static function (array $m): void {
            bo_article_edit_post((int) $m[1]);
        }],
        ['POST', '#^/admin/articles/(\d+)/delete$#', 'admin', static function (array $m): void {
            bo_article_delete_post((int) $m[1]);
        }],

        ['GET', '#^/backoffice/login\.html$#', 'legacy', static function (array $m): void {
            bo_login_get();
        }],
        ['POST', '#^/backoffice/login\.html$#', 'legacy', static function (array $m): void {
            bo_login_post();
        }],
        ['POST', '#^/backoffice/logout\.html$#', 'legacy', static function (array $m): void {
            bo_logout_post();
        }],
        ['GET', '#^/backoffice\.html$#', 'legacy', static function (array $m): void {
            bo_dashboard_get();
        }],
        ['GET', '#^/backoffice/articles/create\.html$#', 'legacy', static function (array $m): void {
            bo_article_create_get();
        }],
        ['POST', '#^/backoffice/articles/create\.html$#', 'legacy', static function (array $m): void {
            bo_article_create_post();
        }],
        ['GET', '#^/backoffice/articles/edit-(\d+)\.html$#', 'legacy', static function (array $m): void {
            bo_article_edit_get((int) $m[1]);
        }],
        ['POST', '#^/backoffice/articles/edit-(\d+)\.html$#', 'legacy', static function (array $m): void {
            bo_article_edit_post((int) $m[1]);
        }],
        ['POST', '#^/backoffice/articles/delete-(\d+)\.html$#', 'legacy', static function (array $m): void {
            bo_article_delete_post((int) $m[1]);
        }],
    ];

    foreach ($routes as [$routeMethod, $pattern, $context, $handler]) {
        if ($method !== $routeMethod) {
            continue;
        }

        if (!preg_match($pattern, $path, $matches)) {
            continue;
        }

        bo_set_route_context($context);
        $handler($matches);
        return true;
    }

    return false;
}
