<?php
$baseUrl = '/';
$articles = is_array($articles ?? null) ? $articles : [];
$mainArticle = $articles[0] ?? null;
$sidebarArticles = array_slice($articles, 1, 3);
$gridArticles = array_slice($articles, 0, 3);
?>

<?php
$articles = is_array($articles ?? null) ? $articles : [];
$mainArticle = $articles[0] ?? null;
$sidebarArticles = array_slice($articles, 1, 3);
$gridArticles = array_slice($articles, 0, 3);
?>

<div class="section-header">
    <h2>Back-Office</h2>
    <a href="/backoffice/articles/create.html">Ajouter un article</a>
</div>

<section class="hero">
    <?php if ($mainArticle !== null): ?>
        <article class="hero-main">
            <?php if (!empty($mainArticle['image_url'])): ?>
                <img src="<?= htmlspecialchars((string) $mainArticle['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars((string) ($mainArticle['image_alt'] ?? $mainArticle['title']), ENT_QUOTES, 'UTF-8'); ?>">
            <?php endif; ?>
            <span class="section-label">Gestion</span>
            <h1><?= htmlspecialchars((string) $mainArticle['title'], ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="standfirst"><?= htmlspecialchars(mb_substr(trim(strip_tags((string) $mainArticle['content'])), 0, 240), ENT_QUOTES, 'UTF-8'); ?>...</p>
            <div class="hero-meta">
                <span>Publié le <?= htmlspecialchars((string) $mainArticle['published_at'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <div class="mt-3 d-flex gap-2 flex-wrap">
                <a class="btn btn-primary" href="/backoffice/articles/create.html">Ajouter</a>
                <a class="btn btn-warning" href="/backoffice/articles/edit-<?= (int) $mainArticle['id']; ?>.html">Modifier</a>
                <form class="d-inline" method="post" action="/backoffice/articles/delete-<?= (int) $mainArticle['id']; ?>.html" onsubmit="return confirm('Supprimer cet article ?');">
                    <button class="btn btn-danger" type="submit">Supprimer</button>
                </form>
            </div>
        </article>
    <?php endif; ?>

    <aside class="hero-sidebar">
        <?php foreach ($sidebarArticles as $article): ?>
            <article class="side-article">
                <?php if (!empty($article['image_url'])): ?>
                    <img src="<?= htmlspecialchars((string) $article['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars((string) ($article['image_alt'] ?? $article['title']), ENT_QUOTES, 'UTF-8'); ?>">
                <?php endif; ?>
                <span class="section-label">Article</span>
                <h3><?= htmlspecialchars((string) $article['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                <div class="mt-2 d-flex gap-2 flex-wrap">
                    <a class="btn btn-warning btn-sm" href="/backoffice/articles/edit-<?= (int) $article['id']; ?>.html">Modifier</a>
                    <form class="d-inline" method="post" action="/backoffice/articles/delete-<?= (int) $article['id']; ?>.html" onsubmit="return confirm('Supprimer cet article ?');">
                        <button class="btn btn-danger btn-sm" type="submit">Supprimer</button>
                    </form>
                </div>
            </article>
        <?php endforeach; ?>
    </aside>
</section>

<div class="section-header">
    <h2>Derniers articles</h2>
    <a href="/backoffice/articles/create.html">Créer un article</a>
</div>

<section class="three-col">
    <?php foreach ($gridArticles as $article): ?>
        <article class="col-article">
            <?php if (!empty($article['image_url'])): ?>
                <img src="<?= htmlspecialchars((string) $article['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars((string) ($article['image_alt'] ?? $article['title']), ENT_QUOTES, 'UTF-8'); ?>">
            <?php endif; ?>
            <span class="section-label"><?= htmlspecialchars((string) ($article['status'] ?? 'draft'), ENT_QUOTES, 'UTF-8'); ?></span>
            <h3><?= htmlspecialchars((string) $article['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
            <p><?= htmlspecialchars(mb_substr(trim(strip_tags((string) $article['content'])), 0, 150), ENT_QUOTES, 'UTF-8'); ?>...</p>
            <div class="meta">Publié le <?= htmlspecialchars((string) $article['published_at'], ENT_QUOTES, 'UTF-8'); ?></div>
            <div class="mt-2 d-flex gap-2 flex-wrap">
                <a class="btn btn-warning btn-sm" href="/backoffice/articles/edit-<?= (int) $article['id']; ?>.html">Modifier</a>
                <form class="d-inline" method="post" action="/backoffice/articles/delete-<?= (int) $article['id']; ?>.html" onsubmit="return confirm('Supprimer cet article ?');">
                    <button class="btn btn-danger btn-sm" type="submit">Supprimer</button>
                </form>
            </div>
        </article>
    <?php endforeach; ?>
</section>
