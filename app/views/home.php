<?php
$baseUrl = Flight::get('flight.base_url');
$articles = is_array($articles ?? null) ? array_values($articles) : [];
$mainArticle = $articles[0] ?? null;
$sidebarArticles = array_slice($articles, 1, 3);
$gridArticles = array_slice($articles, 0, 3);

$formatDateTime = static function (?string $value): array {
    $value = trim((string) $value);

    if ($value === '') {
        return ['', ''];
    }

    try {
        $date = new DateTimeImmutable($value);
        return [$date->format(DATE_ATOM), $date->format('d/m/Y H:i')];
    } catch (Throwable $e) {
        return ['', htmlspecialchars($value, ENT_QUOTES, 'UTF-8')];
    }
};

$structuredArticles = [];
foreach (array_slice($articles, 0, 10) as $index => $article) {
    [$isoDate] = $formatDateTime((string) ($article['published_at'] ?? ''));
    $structuredArticles[] = [
        '@type' => 'ListItem',
        'position' => $index + 1,
        'url' => $baseUrl . 'articles/article-' . (int) ($article['id'] ?? 0) . '-1-1.html',
        'name' => (string) ($article['title'] ?? ''),
        'datePublished' => $isoDate !== '' ? $isoDate : null,
    ];
}

$jsonLd = [
    [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => 'Mini Projet Web',
        'url' => $baseUrl,
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => $baseUrl . 'search?q={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ],
    ],
    [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => 'Accueil | Mini Projet Web',
        'url' => $baseUrl,
        'description' => 'Actualites et analyses sur la guerre en Iran.',
        'mainEntity' => [
            '@type' => 'ItemList',
            'itemListElement' => $structuredArticles,
        ],
    ],
];
?>

<?php if (empty($articles)): ?>
    <section class="article-page" role="status" aria-live="polite" aria-labelledby="no-articles-title">
        <h1 id="no-articles-title">Aucun article publié</h1>
        <p>Ajoutez des articles publiés dans la base pour alimenter la page d'accueil.</p>
    </section>
<?php else: ?>
    <script type="application/ld+json">
        <?= json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
    </script>

    <section class="hero" aria-labelledby="featured-article-title">
        <?php if ($mainArticle !== null): ?>
            <article class="hero-main" aria-labelledby="featured-article-title">
                <?php if (!empty($mainArticle['image_url'])): ?>
                    <img
                        src="<?= htmlspecialchars((string) $mainArticle['image_url'], ENT_QUOTES, 'UTF-8'); ?>"
                        alt="<?= htmlspecialchars((string) ($mainArticle['image_alt'] ?? $mainArticle['title']), ENT_QUOTES, 'UTF-8'); ?>"
                        loading="eager"
                        fetchpriority="high"
                        decoding="async"
                    >
                <?php endif; ?>
                <span class="section-label">International</span>
                <h1 id="featured-article-title">
                    <a href="<?= htmlspecialchars($baseUrl . 'articles/article-' . (int) $mainArticle['id'] . '-1-1.html', ENT_QUOTES, 'UTF-8'); ?>" aria-label="Lire l'article à la une : <?= htmlspecialchars((string) $mainArticle['title'], ENT_QUOTES, 'UTF-8'); ?>">
                        <?= htmlspecialchars((string) $mainArticle['title'], ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                </h1>
                <p class="standfirst"><?= htmlspecialchars(mb_substr(trim(strip_tags((string) $mainArticle['content'])), 0, 240), ENT_QUOTES, 'UTF-8'); ?>...</p>
                <div class="hero-meta">
                    <?php [$mainIsoDate, $mainHumanDate] = $formatDateTime((string) ($mainArticle['published_at'] ?? '')); ?>
                    <?php if ($mainIsoDate !== ''): ?>
                        <time datetime="<?= htmlspecialchars($mainIsoDate, ENT_QUOTES, 'UTF-8'); ?>">Publié le <?= htmlspecialchars($mainHumanDate, ENT_QUOTES, 'UTF-8'); ?></time>
                    <?php else: ?>
                        <span>Publié le <?= htmlspecialchars((string) $mainArticle['published_at'], ENT_QUOTES, 'UTF-8'); ?></span>
                    <?php endif; ?>
                </div>
            </article>
        <?php endif; ?>

        <aside class="hero-sidebar" aria-labelledby="sidebar-articles-title">
            <h2 id="sidebar-articles-title" class="visually-hidden">Autres articles récents</h2>
            <?php foreach ($sidebarArticles as $article): ?>
                <article class="side-article" aria-labelledby="sidebar-article-<?= (int) $article['id']; ?>">
                    <?php if (!empty($article['image_url'])): ?>
                        <img
                            src="<?= htmlspecialchars((string) $article['image_url'], ENT_QUOTES, 'UTF-8'); ?>"
                            alt="<?= htmlspecialchars((string) ($article['image_alt'] ?? $article['title']), ENT_QUOTES, 'UTF-8'); ?>"
                            loading="lazy"
                            decoding="async"
                        >
                    <?php endif; ?>
                    <span class="section-label">Actualite</span>
                    <h3 id="sidebar-article-<?= (int) $article['id']; ?>">
                        <a href="<?= htmlspecialchars($baseUrl . 'articles/article-' . (int) $article['id'] . '-1-1.html', ENT_QUOTES, 'UTF-8'); ?>" aria-label="Lire l'article : <?= htmlspecialchars((string) $article['title'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?= htmlspecialchars((string) $article['title'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </h3>
                </article>
            <?php endforeach; ?>
        </aside>
    </section>

    <section aria-labelledby="news-grid-title">
        <div class="section-header">
            <h2 id="news-grid-title">Actualites</h2>
            <a href="#all-articles" aria-label="Voir tous les articles">Voir tout</a>
        </div>

        <section class="three-col" aria-label="Liste des trois premiers articles">
            <?php foreach ($gridArticles as $article): ?>
                <article class="col-article" aria-labelledby="grid-article-<?= (int) $article['id']; ?>">
                    <?php if (!empty($article['image_url'])): ?>
                        <img
                            src="<?= htmlspecialchars((string) $article['image_url'], ENT_QUOTES, 'UTF-8'); ?>"
                            alt="<?= htmlspecialchars((string) ($article['image_alt'] ?? $article['title']), ENT_QUOTES, 'UTF-8'); ?>"
                            loading="lazy"
                            decoding="async"
                        >
                    <?php endif; ?>
                    <span class="section-label">Analyse</span>
                    <h3 id="grid-article-<?= (int) $article['id']; ?>">
                        <a href="<?= htmlspecialchars($baseUrl . 'articles/article-' . (int) $article['id'] . '-1-1.html', ENT_QUOTES, 'UTF-8'); ?>" aria-label="Lire l'article : <?= htmlspecialchars((string) $article['title'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?= htmlspecialchars((string) $article['title'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </h3>
                    <p><?= htmlspecialchars(mb_substr(trim(strip_tags((string) $article['content'])), 0, 150), ENT_QUOTES, 'UTF-8'); ?>...</p>
                    <div class="meta">
                        <?php [$gridIsoDate, $gridHumanDate] = $formatDateTime((string) ($article['published_at'] ?? '')); ?>
                        <?php if ($gridIsoDate !== ''): ?>
                            <time datetime="<?= htmlspecialchars($gridIsoDate, ENT_QUOTES, 'UTF-8'); ?>">Publié le <?= htmlspecialchars($gridHumanDate, ENT_QUOTES, 'UTF-8'); ?></time>
                        <?php else: ?>
                            <span>Publié le <?= htmlspecialchars((string) $article['published_at'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

        <div class="section-header" id="all-articles">
            <h2>Tous les articles</h2>
        </div>

        <section class="three-col" aria-label="Liste complète des articles">
            <?php foreach ($articles as $article): ?>
                <article class="col-article" aria-labelledby="all-article-<?= (int) $article['id']; ?>">
                    <?php if (!empty($article['image_url'])): ?>
                        <img
                            src="<?= htmlspecialchars((string) $article['image_url'], ENT_QUOTES, 'UTF-8'); ?>"
                            alt="<?= htmlspecialchars((string) ($article['image_alt'] ?? $article['title']), ENT_QUOTES, 'UTF-8'); ?>"
                            loading="lazy"
                            decoding="async"
                        >
                    <?php endif; ?>
                    <span class="section-label">Article</span>
                    <h3 id="all-article-<?= (int) $article['id']; ?>">
                        <a href="<?= htmlspecialchars($baseUrl . 'articles/article-' . (int) $article['id'] . '-1-1.html', ENT_QUOTES, 'UTF-8'); ?>" aria-label="Lire l'article : <?= htmlspecialchars((string) $article['title'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?= htmlspecialchars((string) $article['title'], ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </h3>
                    <p><?= htmlspecialchars(mb_substr(trim(strip_tags((string) $article['content'])), 0, 150), ENT_QUOTES, 'UTF-8'); ?>...</p>
                    <div class="meta">
                        <?php [$allIsoDate, $allHumanDate] = $formatDateTime((string) ($article['published_at'] ?? '')); ?>
                        <?php if ($allIsoDate !== ''): ?>
                            <time datetime="<?= htmlspecialchars($allIsoDate, ENT_QUOTES, 'UTF-8'); ?>">Publié le <?= htmlspecialchars($allHumanDate, ENT_QUOTES, 'UTF-8'); ?></time>
                        <?php else: ?>
                            <span>Publié le <?= htmlspecialchars((string) $article['published_at'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    </section>
<?php endif; ?>
