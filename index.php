<?php

declare(strict_types=1);

require_once __DIR__ . '/function/common.php';
require_once __DIR__ . '/function/function_frontOffice.php';
require_once __DIR__ . '/function/function_backoffice.php';
require_once __DIR__ . '/pages/traitement_frontOffice.php';
require_once __DIR__ . '/pages/traitement_backoffice.php';

app_start_session();

$method = app_request_method();
$path = app_request_path();

if (handle_frontoffice_request($method, $path)) {
  exit;
}

if (handle_backoffice_request($method, $path)) {
  exit;
}

fo_render_not_found($path);
