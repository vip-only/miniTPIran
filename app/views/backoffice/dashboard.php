<?php
$articles = is_array($articles ?? null) ? $articles : [];
$gridArticles = array_slice($articles, 0, 3);
?>

<div class="section-header">
    <h2>Back-Office</h2>
    <a class="btn btn-sm d-inline-flex align-items-center gap-2"
       href="/backoffice/articles/create.html"
       title="Ajouter un article"
       aria-label="Ajouter un article"
       style="padding: .4rem .75rem; line-height: 1; background:#fff5f5; color:#c0392b; border:1px solid #c0392b;">
        <span>Ajouter</span>
        <span aria-hidden="true">+</span>
    </a>
</div>

<?php if (!empty($info)): ?>
    <div class="alert alert-success py-2 px-3 mb-3">
        <?= htmlspecialchars((string) $info, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php endif; ?>

<section class="card border-0 shadow-sm mb-4">
    <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <h3 class="mb-1">Gestion des articles</h3>
            <p class="mb-0 text-muted">Créer, modifier ou supprimer rapidement les contenus publiés.</p>
        </div>
        <span class="badge bg-light text-dark border"><?= count($articles); ?> article<?= count($articles) > 1 ? 's' : ''; ?></span>
    </div>
</section>

<section class="three-col">
    <?php foreach ($gridArticles as $article): ?>
        <article class="col-article d-flex flex-column h-100">
            <div class="flex-grow-1 d-flex flex-column">
                <?php if (!empty($article['image_url'])): ?>
                    <img src="<?= htmlspecialchars((string) $article['image_url'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars((string) ($article['image_alt'] ?? $article['title']), ENT_QUOTES, 'UTF-8'); ?>">
                <?php endif; ?>
                <span class="section-label"><?= htmlspecialchars((string) ($article['status'] ?? 'draft'), ENT_QUOTES, 'UTF-8'); ?></span>
                <h3><?= htmlspecialchars((string) $article['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                <p><?= htmlspecialchars(mb_substr(trim(strip_tags((string) $article['content'])), 0, 150), ENT_QUOTES, 'UTF-8'); ?>...</p>
                <div class="meta mb-2">Publié le <?= htmlspecialchars((string) $article['published_at'], ENT_QUOTES, 'UTF-8'); ?></div>
            </div>

            <div class="mt-auto d-flex gap-2 flex-wrap align-items-center justify-content-end">
                <a class="btn btn-outline-warning btn-sm d-inline-flex align-items-center justify-content-center"
                   href="/backoffice/articles/edit-<?= (int) $article['id']; ?>.html"
                   title="Modifier"
                   aria-label="Modifier"
                   style="width: 2rem; height: 2rem; padding: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true"><path d="M12.146.854a.5.5 0 0 1 .708 0l2.292 2.292a.5.5 0 0 1 0 .708L5.207 13.793 1 15l1.207-4.207L12.146.854zM11.207 2 2 11.207V13h1.793L13 3.793 11.207 2z"/></svg>
                </a>
                <form class="d-inline" method="post" action="/backoffice/articles/delete-<?= (int) $article['id']; ?>.html" onsubmit="return confirm('Supprimer cet article ?');">
                    <button class="btn btn-outline-danger btn-sm d-inline-flex align-items-center justify-content-center" type="submit" title="Supprimer" aria-label="Supprimer" style="width: 2rem; height: 2rem; padding: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true"><path d="M5.5 5.5a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-1 0v-5a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-1 0v-5a.5.5 0 0 1 .5-.5zm3-.5H3.5a.5.5 0 0 0 0 1H4v7.5A1.5 1.5 0 0 0 5.5 15h5A1.5 1.5 0 0 0 12 13.5V6h.5a.5.5 0 0 0 0-1H10V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v1H3.5a.5.5 0 0 0 0 1H11v7.5a.5.5 0 0 1-.5.5h-5a.5.5 0 0 1-.5-.5V6h6V5zm-4-1V4a1 1 0 0 1 2 0v1H7.5z"/></svg>
                    </button>
                </form>
            </div>
        </article>
    <?php endforeach; ?>
</section>
