<?php
$baseUrl = $baseUrl ?? app_base_url();
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
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Source+Serif+4:wght@300;400;600&family=Noto+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
    *, *::before, *::after { box-sizing: border-box; }
    :root {
        --black: #1a1a1a;
        --ink: #222222;
        --mid: #555555;
        --light: #888888;
        --border: #d9d9d9;
        --bg: #f5f4f0;
        --white: #ffffff;
        --red: #c0392b;
        --blue: #0a3d6b;
        --gold: #b8860b;
        --serif: 'Playfair Display', Georgia, serif;
        --body-serif: 'Source Serif 4', Georgia, serif;
        --sans: 'Noto Sans', Helvetica, sans-serif;
    }

    body {
        margin: 0;
        font-family: var(--body-serif);
        background: var(--bg);
        color: var(--ink);
        line-height: 1.6;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    a { text-decoration: none; color: inherit; }
    img { display: block; width: 100%; height: auto; object-fit: cover; }

    .breaking-bar {
        background: var(--red);
        color: #fff;
        font-family: var(--sans);
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.45rem 1.5rem;
    }
    .breaking-bar .label {
        background: #fff;
        color: var(--red);
        padding: 0.15rem 0.5rem;
        border-radius: 2px;
        font-size: 0.7rem;
    }

    .top-bar {
        border-bottom: 1px solid var(--border);
        background: var(--white);
        padding: 0.4rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-family: var(--sans);
        font-size: 0.72rem;
        color: var(--mid);
    }
    .top-links {
        display: flex;
        gap: 1.1rem;
    }
    .subscribe-btn {
        background: var(--blue);
        color: #fff;
        padding: 0.3rem 0.9rem;
        border-radius: 2px;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        font-size: 0.68rem;
    }

    .site-header {
        background: var(--white);
        border-bottom: 3px solid var(--black);
        padding: 1rem 1.5rem 0.8rem;
    }
    .header-inner {
        max-width: 1280px;
        margin: 0 auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .site-logo {
        font-family: var(--serif);
        font-size: clamp(2.2rem, 6vw, 4rem);
        font-weight: 900;
        letter-spacing: -0.02em;
        color: var(--black);
        line-height: 1;
    }
    .site-logo span {
        color: var(--red);
    }

    nav.main-nav {
        background: var(--white);
        border-bottom: 1px solid var(--border);
        position: sticky;
        top: 0;
        z-index: 20;
    }
    .nav-inner {
        max-width: 1280px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        overflow-x: auto;
    }
    .nav-inner a {
        white-space: nowrap;
        font-family: var(--sans);
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: var(--ink);
        padding: 0.75rem 1rem;
        border-bottom: 3px solid transparent;
    }
    .nav-inner a.active,
    .nav-inner a:hover {
        border-color: var(--red);
        color: var(--red);
    }

    .site-container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 1.5rem;
        width: 100%;
    }

    .hero {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 0;
        border-bottom: 1px solid var(--border);
        padding: 2rem 0 1.5rem;
    }
    .hero-main {
        padding-right: 2rem;
        border-right: 1px solid var(--border);
    }
    .hero-main img {
        aspect-ratio: 16/9;
        margin-bottom: 1rem;
    }
    .hero-main h1 {
        font-family: var(--serif);
        font-size: clamp(1.6rem, 3vw, 2.6rem);
        line-height: 1.15;
        color: var(--black);
        margin-bottom: 0.6rem;
    }
    .standfirst {
        font-size: 1.02rem;
        color: var(--mid);
        margin-bottom: 0.8rem;
    }
    .hero-meta {
        font-family: var(--sans);
        font-size: 0.72rem;
        color: var(--light);
        display: flex;
        gap: 1rem;
    }

    .hero-sidebar {
        padding-left: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.1rem;
    }
    .side-article {
        padding-bottom: 1.1rem;
        border-bottom: 1px solid var(--border);
    }
    .side-article:last-child { border-bottom: none; }
    .side-article img {
        aspect-ratio: 3/2;
        margin-bottom: 0.55rem;
    }
    .side-article h3 {
        font-family: var(--serif);
        font-size: 1rem;
        line-height: 1.3;
    }

    .section-header {
        display: flex;
        align-items: baseline;
        gap: 1rem;
        padding: 1.5rem 0 0.8rem;
        border-top: 3px solid var(--black);
        margin-top: 1.5rem;
    }
    .section-header h2 {
        font-family: var(--serif);
        font-size: 1.4rem;
        color: var(--black);
    }
    .section-header a {
        margin-left: auto;
        color: var(--red);
        font-family: var(--sans);
        font-size: 0.72rem;
        text-transform: uppercase;
        font-weight: 700;
    }

    .three-col {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        border-bottom: 1px solid var(--border);
        padding-bottom: 1.5rem;
    }
    .col-article {
        padding: 0 1.5rem;
        border-left: 1px solid var(--border);
    }
    .col-article:first-child {
        border-left: none;
        padding-left: 0;
    }
    .col-article img {
        aspect-ratio: 4/3;
        margin-bottom: 0.7rem;
    }
    .col-article h3 {
        font-family: var(--serif);
        font-size: 1.08rem;
        line-height: 1.3;
        margin-bottom: 0.4rem;
        color: var(--black);
    }

    .section-label {
        display: inline-block;
        margin-bottom: 0.4rem;
        color: var(--red);
        font-family: var(--sans);
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    .meta {
        color: var(--light);
        font-size: 0.74rem;
        font-family: var(--sans);
    }

    .article-page {
        max-width: 900px;
        margin: 2rem auto;
        background: var(--white);
        border: 1px solid var(--border);
        padding: 1.5rem;
    }
    .article-page h1 {
        font-family: var(--serif);
        font-size: clamp(1.6rem, 3.4vw, 2.4rem);
        line-height: 1.18;
        color: var(--black);
        margin-bottom: 0.5rem;
    }

    .site-footer {
        margin-top: auto;
        background: var(--black);
        color: #aaa;
        padding: 1.2rem 0;
    }
    .footer-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .footer-logo {
        font-family: var(--serif);
        color: #fff;
        font-size: 1.25rem;
        font-weight: 900;
    }

    @media (max-width: 900px) {
        .hero { grid-template-columns: 1fr; }
        .hero-main { padding-right: 0; border-right: none; }
        .hero-sidebar { padding-left: 0; border-top: 1px solid var(--border); padding-top: 1rem; }
        .three-col { grid-template-columns: 1fr 1fr; }
        .three-col .col-article:nth-child(3) { display: none; }
    }
    @media (max-width: 600px) {
        .three-col { grid-template-columns: 1fr; }
        .top-links { display: none; }
    }
</style>
