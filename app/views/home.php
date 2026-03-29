<?php
$baseUrl = Flight::get('flight.base_url');
$mainArticle = $articles[0] ?? null;
$sidebarArticles = array_slice($articles, 1, 3);
$gridArticles = array_slice($articles, 0, 3);
?>

<?php if (empty($articles)): ?>
    <section class="article-page">
        <h1>Aucun article publie</h1>
        <p>Ajoutez des articles publies dans la base pour alimenter la page d'accueil.</p>
    </section>
<?php else: ?>
    <section class="hero">
        <?php if ($mainArticle !== null): ?>
            <article class="hero-main">
                <?php if (!empty($mainArticle['image_url'])): ?>
                    <img src="<?= htmlspecialchars((string) $mainArticle['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars((string) ($mainArticle['image_alt'] ?? $mainArticle['title']), ENT_QUOTES, 'UTF-8'); ?>">
                <?php endif; ?>
                <span class="section-label">International</span>
                <h1>
                    <a href="<?= htmlspecialchars($baseUrl . 'articles/article-' . (int) $mainArticle['id'] . '-1-1.html', ENT_QUOTES, 'UTF-8'); ?>">
                        <?= htmlspecialchars((string) $mainArticle['title'], ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                </h1>
                <p class="standfirst"><?= htmlspecialchars(mb_substr(trim(strip_tags((string) $mainArticle['content'])), 0, 240), ENT_QUOTES, 'UTF-8'); ?>...</p>
                <div class="hero-meta">
                    <span>Publie le <?= htmlspecialchars((string) $mainArticle['published_at'], ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
            </article>
        <?php endif; ?>

        <aside class="hero-sidebar">
            <?php foreach ($sidebarArticles as $article): ?>
                <article class="side-article">
                    <?php if (!empty($article['image_url'])): ?>
                        <img src="<?= htmlspecialchars((string) $article['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars((string) ($article['image_alt'] ?? $article['title']), ENT_QUOTES, 'UTF-8'); ?>">
                    <?php endif; ?>
                    <span class="section-label">Actualite</span>
                    <h3>
                        <a href="<?= htmlspecialchars($baseUrl . 'articles/article-' . (int) $article['id'] . '-1-1.html', ENT_QUOTES, 'UTF-8'); ?>">
                            <?= htmlspecialchars((string) $article['title'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </h3>
                </article>
            <?php endforeach; ?>
        </aside>
    </section>

    <div class="section-header">
        <h2>Actualites</h2>
        <a href="<?= htmlspecialchars($baseUrl, ENT_QUOTES, 'UTF-8'); ?>">Voir tout</a>
    </div>

    <section class="three-col">
        <?php foreach ($gridArticles as $article): ?>
            <article class="col-article">
                <?php if (!empty($article['image_url'])): ?>
                    <img src="<?= htmlspecialchars((string) $article['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars((string) ($article['image_alt'] ?? $article['title']), ENT_QUOTES, 'UTF-8'); ?>">
                <?php endif; ?>
                <span class="section-label">Analyse</span>
                <h3>
                    <a href="<?= htmlspecialchars($baseUrl . 'articles/article-' . (int) $article['id'] . '-1-1.html', ENT_QUOTES, 'UTF-8'); ?>">
                        <?= htmlspecialchars((string) $article['title'], ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                </h3>
                <p><?= htmlspecialchars(mb_substr(trim(strip_tags((string) $article['content'])), 0, 150), ENT_QUOTES, 'UTF-8'); ?>...</p>
                <div class="meta">Publie le <?= htmlspecialchars((string) $article['published_at'], ENT_QUOTES, 'UTF-8'); ?></div>
            </article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>
