<?php
$baseUrl = Flight::get('flight.base_url');
$pageName = $page ?? '404';
$allowedPages = ['home', 'article', '404'];
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
    <header class="site-header">
        <div class="container">
            <a class="brand" href="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8'); ?>">Mini Projet Web</a>
            <nav class="main-nav" aria-label="Navigation principale">
                <a href="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8'); ?>">Accueil</a>
            </nav>
        </div>
    </header>

    <main class="container">
        <?php include __DIR__ . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . 'flash.php'; ?>
        <?php include $viewFile; ?>
        <?php include __DIR__ . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . 'pagination.php'; ?>
    </main>

    <footer class="site-footer">
        <div class="container">
            <small>Mini-projet Web Design 2026</small>
        </div>
    </footer>
</body>
</html>
