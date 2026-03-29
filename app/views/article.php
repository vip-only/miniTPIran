<article class="article-page">
    <span class="section-label">Article</span>
    <h1><?= htmlspecialchars(isset($article['title']) ? $article['title'] : 'Article', ENT_QUOTES, 'UTF-8'); ?></h1>
    <p class="meta">Publie le <?= htmlspecialchars((string) (isset($article['published_at']) ? $article['published_at'] : ''), ENT_QUOTES, 'UTF-8'); ?></p>

    <?php if (!empty($article['image_url'])): ?>
        <figure>
            <img
                src="<?= htmlspecialchars((string) $article['image_url'], ENT_QUOTES, 'UTF-8'); ?>"
                alt="<?= htmlspecialchars((string) (isset($article['image_alt']) ? $article['image_alt'] : (isset($article['title']) ? $article['title'] : 'Illustration article')), ENT_QUOTES, 'UTF-8'); ?>"
                loading="lazy"
                style="max-width: 100%; height: auto; margin-bottom: 1rem;"
            >
        </figure>
    <?php endif; ?>

    <div>
        <?= (string) (isset($article['content']) ? $article['content'] : ''); ?>
    </div>
</article>
