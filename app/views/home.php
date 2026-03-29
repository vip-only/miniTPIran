<section>
    <h1>Actualites: Guerre en Iran</h1>
    <?php if (empty($articles)): ?>
        <p>Aucun article publie pour le moment.</p>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
            <article class="article-card">
                <h2>
                    <a href="<?= htmlspecialchars(Flight::get('flight.base_url') . 'article/' . $article['slug'], ENT_QUOTES, 'UTF-8'); ?>">
                        <?= htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                </h2>
                <p class="meta">Publie le <?= htmlspecialchars((string) $article['published_at'], ENT_QUOTES, 'UTF-8'); ?></p>
                <p><?= htmlspecialchars(mb_substr(trim(strip_tags((string) $article['content'])), 0, 220), ENT_QUOTES, 'UTF-8'); ?>...</p>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
