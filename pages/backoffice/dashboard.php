<?php
$articles = is_array($articles ?? null) ? $articles : [];
$gridArticles = $articles;
$routes = is_array($routes ?? null) ? $routes : [];
$createUrl = (string) ($routes['article_create'] ?? '/backoffice/articles/create.html');
$editPattern = (string) ($routes['article_edit_pattern'] ?? '/backoffice/articles/edit-%d.html');
$deletePattern = (string) ($routes['article_delete_pattern'] ?? '/backoffice/articles/delete-%d.html');
?>

<style>
    .three-col {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.25rem;
    }

    .col-article {
        background: #fff;
        border: 1px solid rgba(0,0,0,.08);
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(0,0,0,.04);
        overflow: hidden;
        transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .col-article:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(0,0,0,.08);
        border-color: rgba(0,0,0,.14);
    }

    .col-article img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        display: block;
        background: #f3f3f3;
    }

    .col-article .card-content {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: .5rem;
        flex: 1;
        min-height: 0;
    }

    .col-article h3 {
        font-size: 1.05rem;
        margin: 0;
        line-height: 1.3;
    }

    .col-article p {
        margin: 0;
        color: #555;
        font-size: .95rem;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .col-article .meta {
        color: #777;
        font-size: .82rem;
        margin-top: auto;
    }

    .col-article .actions {
        margin-top: .75rem;
        display: flex;
        justify-content: flex-end;
        gap: .5rem;
        flex-shrink: 0;
    }

    .col-article .section-label {
        display: inline-block;
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #666;
    }

    .article-status {
        color: #b91c1c;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
        font-size: .72rem;
    }
</style>

<?php if (!empty($_SESSION['is_admin'])): ?>
<div class="d-flex gap-2 align-items-center mb-3">
    
</div>
<?php endif; ?>

<div class="section-header">
    <h2>Back-Office</h2>
    <a class="btn btn-sm d-inline-flex align-items-center gap-2"
         href="<?= htmlspecialchars($createUrl, ENT_QUOTES, 'UTF-8'); ?>"
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
            <?php if (!empty($article['image_url'])): ?>
                <img
                    src="<?= htmlspecialchars((string) $article['image_url'], ENT_QUOTES, 'UTF-8'); ?>"
                    alt="<?= htmlspecialchars((string) ($article['image_alt'] ?? $article['title']), ENT_QUOTES, 'UTF-8'); ?>"
                    loading="lazy"
                    decoding="async"
                >
            <?php endif; ?>

            <div class="card-content">
                <span class="article-status">
                    <?= htmlspecialchars((string) ($article['status'] ?? 'draft'), ENT_QUOTES, 'UTF-8'); ?>
                </span>

                <h3><?= htmlspecialchars((string) $article['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                <p><?= htmlspecialchars(mb_substr(trim(strip_tags((string) $article['content'])), 0, 150), ENT_QUOTES, 'UTF-8'); ?>...</p>
                <div class="meta">Publié le <?= htmlspecialchars((string) $article['published_at'], ENT_QUOTES, 'UTF-8'); ?></div>

                <div class="actions">
                    <a class="btn btn-sm d-inline-flex align-items-center justify-content-center"
                              href="<?= htmlspecialchars(sprintf($editPattern, (int) $article['id']), ENT_QUOTES, 'UTF-8'); ?>"
                       title="Modifier"
                       aria-label="Modifier"
                       style="width: 2.15rem; height: 2.15rem; padding: 0; color: #b45309; border: 1px solid #f59e0b; background: #fffaf0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                            <path d="M12.146.854a.5.5 0 0 1 .708 0l2.292 2.292a.5.5 0 0 1 0 .708L5.207 13.793 1 15l1.207-4.207L12.146.854zM11.207 2 2 11.207V13h1.793L13 3.793 11.207 2z"/>
                        </svg>
                    </a>

                    <form class="d-inline" method="post" action="<?= htmlspecialchars(sprintf($deletePattern, (int) $article['id']), ENT_QUOTES, 'UTF-8'); ?>" onsubmit="return confirm('Supprimer cet article ?');">
                        <button class="btn btn-sm d-inline-flex align-items-center justify-content-center"
                                type="submit"
                                title="Supprimer"
                                aria-label="Supprimer"
                                style="width: 2.15rem; height: 2.15rem; padding: 0; color: #b91c1c; border: 1px solid #ef4444; background: #fff5f5;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                                <path d="M5.5 5.5a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-1 0v-5a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v5a.5.5 0 0 1-1 0v-5a.5.5 0 0 1 .5-.5zm3-.5H3.5a.5.5 0 0 0 0 1H4v7.5A1.5 1.5 0 0 0 5.5 15h5A1.5 1.5 0 0 0 12 13.5V6h.5a.5.5 0 0 0 0-1H10V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v1H3.5a.5.5 0 0 0 0 1H11v7.5a.5.5 0 0 1-.5.5h-5a.5.5 0 0 1-.5-.5V6h6V5zm-4-1V4a1 1 0 0 1 2 0v1H7.5z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </article>
    <?php endforeach; ?>
</section>
