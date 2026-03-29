<article class="article-page">
    <span class="section-label">Article</span>
    <h1><?= htmlspecialchars($article['title'] ?? 'Article', ENT_QUOTES, 'UTF-8'); ?></h1>
    <p class="meta">Publie le <?= htmlspecialchars((string) ($article['published_at'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>

    <?php if (!empty($article['image_url'])): ?>
        <figure>
            <img
                src="<?= htmlspecialchars((string) $article['image_url'], ENT_QUOTES, 'UTF-8'); ?>"
                alt="<?= htmlspecialchars((string) ($article['image_alt'] ?? $article['title'] ?? 'Illustration article'), ENT_QUOTES, 'UTF-8'); ?>"
                loading="lazy"
                style="max-width: 100%; height: auto; margin-bottom: 1rem;"
            >
        </figure>
    <?php endif; ?>

    <?php if (!empty($article['image_alt'])): ?>
        <p class="meta">Texte alternatif image: <?= htmlspecialchars($article['image_alt'], ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <div>
        <?= (string) ($article['content'] ?? ''); ?>
    </div>
</article>
