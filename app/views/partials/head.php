<?php
$baseUrl = Flight::get('flight.base_url');
$pageTitle = $title ?? 'Mini Projet Web';
$pageDescription = $metaDescription ?? 'Site d information sur la guerre en Iran.';
$canonical = $canonicalUrl ?? $baseUrl;
$openGraphType = $ogType ?? 'website';
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
<meta name="description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8'); ?>">
<link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8'); ?>">
<meta property="og:title" content="<?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?>">
<meta property="og:description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8'); ?>">
<meta property="og:url" content="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8'); ?>">
<meta property="og:type" content="<?= htmlspecialchars($openGraphType, ENT_QUOTES, 'UTF-8'); ?>">
<link rel="stylesheet" href="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8'); ?>assets/css/bootstrap.min.css">
<style>
    :root {
        --bg: #f7f3e8;
        --surface: #fffdf7;
        --text: #1a1a1a;
        --muted: #5a5a5a;
        --accent: #8c2f39;
        --border: #e9decb;
    }
    body {
        margin: 0;
        color: var(--text);
        background: radial-gradient(circle at 10% 10%, #fff7e9 0%, var(--bg) 55%, #efe6d8 100%);
        font-family: Georgia, "Times New Roman", serif;
    }
    .container {
        width: min(980px, 92vw);
        margin: 0 auto;
    }
    .site-header {
        background: #fff;
        border-bottom: 1px solid var(--border);
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .site-header .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
    }
    .brand {
        text-decoration: none;
        color: var(--text);
        font-weight: 700;
        letter-spacing: 0.04em;
    }
    .main-nav a {
        text-decoration: none;
        color: var(--accent);
        font-weight: 600;
    }
    main.container {
        padding: 2rem 0 3rem;
    }
    .site-footer {
        border-top: 1px solid var(--border);
        background: #fff;
    }
    .site-footer .container {
        padding: 1rem 0;
        color: var(--muted);
    }
    .article-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1rem 1.2rem;
        margin-bottom: 1rem;
    }
    .article-card h2 {
        margin: 0 0 .4rem;
        font-size: 1.35rem;
    }
    .article-card a {
        color: var(--accent);
        text-decoration: none;
    }
    .article-card a:hover {
        text-decoration: underline;
    }
    .meta {
        color: var(--muted);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
</style>
