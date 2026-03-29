<?php
$baseUrl = Flight::get('flight.base_url');
$pageName = $page ?? '404';
$allowedPages = ['home', 'article', '404', 'backoffice/dashboard'];
if (!in_array($pageName, $allowedPages, true)) {
    $pageName = '404';
}

$viewFile = __DIR__ . DIRECTORY_SEPARATOR . $pageName . '.php';
if (!file_exists($viewFile)) {
    $viewFile = __DIR__ . DIRECTORY_SEPARATOR . '404.php';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php include __DIR__ . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . 'head.php'; ?>
</head>
<body>
    <div class="breaking-bar">
        <span class="label">En direct</span>
        <span class="ticker">Suivez les actualites geopolitques en continu</span>
    </div>

    <div class="top-bar">
        <span class="date"><?= date('l d F Y'); ?></span>
        <div class="top-links">
            <a href="#">Edition du soir</a>
            <a href="#">Podcasts</a>
            <a href="#">Videos</a>
        </div>
        <a href="#" class="subscribe-btn">S'abonner</a>
    </div>

    <header class="site-header">
        <div class="header-inner">
            <a class="site-logo" href="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8'); ?>">Le Monde<span>.</span></a>
        </div>
    </header>

    <nav class="main-nav">
        <div class="nav-inner">
            <a href="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8'); ?>" class="active">A la une</a>
            <a href="#">International</a>
            <a href="#">France</a>
            <a href="#">Economie</a>
            <a href="#">Sciences</a>
            <a href="#">Culture</a>
            <a href="#">Idees</a>
        </div>
    </nav>

    <main class="site-container">
        <?php include __DIR__ . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . 'flash.php'; ?>
        <?php include $viewFile; ?>
        <?php include __DIR__ . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . 'pagination.php'; ?>
    </main>

    <footer class="site-footer">
        <div class="site-container footer-inner">
            <span class="footer-logo">Le Monde</span>
            <small>Mini-projet Web Design 2026</small>
        </div>
    </footer>
</body>
</html>
