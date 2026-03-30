<?php
$mode = $mode ?? 'create';
$article = is_array($article ?? null) ? $article : [];

$titleValue = (string) ($article['title'] ?? '');
$contentValue = (string) ($article['content'] ?? '');
$imageUrlValue = (string) ($article['image_url'] ?? '');
$statusValue = (string) ($article['status'] ?? 'draft');
$publishedAtRaw = (string) ($article['published_at'] ?? '');

$publishedAtValue = '';
if ($publishedAtRaw !== '') {
    try {
        $publishedAtValue = (new DateTimeImmutable($publishedAtRaw))->format('Y-m-d\TH:i');
    } catch (Throwable) {
        $publishedAtValue = '';
    }
}

$formAction = (string) ($formAction ?? '/backoffice/articles/create.html');
$routes = is_array($routes ?? null) ? $routes : [];
$dashboardUrl = (string) ($routes['dashboard'] ?? '/backoffice.html');

$isEdit = $mode === 'edit';
$pageTitle = $isEdit ? 'Modifier un article' : 'Créer un article';
$buttonLabel = $isEdit ? 'Mettre à jour' : 'Créer';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>

    <link rel="preconnect" href="https://cdn.tiny.cloud">
    <link rel="dns-prefetch" href="//cdn.tiny.cloud">
    <script defer src="https://cdn.tiny.cloud/1/3icxdfwpj5rjy4gf88mq31shiqwuhmgx44c3pd9kxmrs4pms/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <style>
        :root{--bg:#f6f2ea;--surface:#fffdf8;--surface-2:#f8f4ee;--text:#1f1f1f;--muted:#6f6a63;--border:#e6ddd1;--accent:#b23a2f;--accent-2:#8f2f26;--shadow:0 10px 30px rgba(0, 0, 0, .06)}.field,.form-shell{border:1px solid var(--border)}body{margin:0;font-family:Arial,sans-serif;background:linear-gradient(180deg,var(--bg),#fff);color:var(--text)}.container{max-width:1100px;margin:2rem auto;padding:0 1rem}.form-shell{background:var(--surface);border-radius:18px;box-shadow:var(--shadow);overflow:hidden}.form-header{padding:1.25rem 1.5rem;background:linear-gradient(135deg,#fff,#f8f2eb);border-bottom:1px solid var(--border)}.form-header h1{margin:0;font-size:1.5rem}.form-header p{margin:.35rem 0 0;color:var(--muted);font-size:.95rem}.form-body{padding:1.5rem}.grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem}.field{background:var(--surface-2);border-radius:14px;padding:1rem}label{display:block;font-weight:700;margin:0 0 .4rem}input,select,textarea{width:100%;box-sizing:border-box;padding:.8rem .9rem;border:1px solid var(--border);border-radius:12px;background:#fff;color:var(--text)}input:focus,select:focus,textarea:focus{outline:rgba(178,58,47,.18) solid 2px;border-color:rgba(178,58,47,.45)}textarea{min-height:220px;resize:vertical}.hint{color:var(--muted);font-size:.9rem;margin-top:.45rem}.error,.info{border-radius:12px;padding:.85rem 1rem;margin-bottom:1rem;border:1px solid}.error{background:#fff0f0;color:#9b1c1c;border-color:#f0b4b4}.info{background:#f1fbf1;color:#1f6b2a;border-color:#bfe3c4}.preview{margin-top:.9rem;display:inline-block;max-width:280px}.preview img{width:100%;height:auto;border-radius:12px;border:1px solid var(--border);display:block}.actions{display:flex;gap:.75rem;margin-top:1.4rem;flex-wrap:wrap}a.btn,button{display:inline-flex;align-items:center;justify-content:center;padding:.8rem 1.1rem;border-radius:12px;font-weight:700;text-decoration:none;border:1px solid transparent}button{background:var(--accent);color:#fff;border-color:var(--accent)}button:hover{background:var(--accent-2)}a.btn{background:#fff;color:var(--text);border-color:var(--border)}@media (max-width:768px){.grid{grid-template-columns:1fr}.form-body{padding:1rem}.form-header{padding:1rem 1rem .9rem}}
    </style>

    <script>
        window.addEventListener('load', function () {
            if (!window.tinymce) return;

            tinymce.init({
                selector: '#content',
                plugins: 'lists link image table code help wordcount',
                toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
                block_formats: 'Paragraphe=p; Titre 2=h2; Titre 3=h3; Titre 4=h4',
                image_description: true,
                image_advtab: true
            });

            const form = document.querySelector('form[data-tinymce-form="1"]');
            if (form) {
                form.addEventListener('submit', function () {
                    if (window.tinymce) {
                        tinymce.triggerSave();
                    }
                });
            }
        });
    </script>
</head>
<body>
    <main class="container">
        <section class="form-shell">
            <header class="form-header">
                <h1><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
                <p>Créer ou modifier un article dans le même esprit visuel que le reste du site.</p>
            </header>

            <div class="form-body">
                <?php if (!empty($error)): ?>
                    <div class="error"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>

                <?php if (!empty($info)): ?>
                    <div class="info"><?= htmlspecialchars((string) $info, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>

                <form method="post" action="<?= htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8'); ?>" enctype="multipart/form-data" data-tinymce-form="1">
                    <div class="grid">
                        <div class="field">
                            <label for="title">Titre</label>
                            <input type="text" id="title" name="title" value="<?= htmlspecialchars($titleValue, ENT_QUOTES, 'UTF-8'); ?>" required autocomplete="off">
                        </div>

                        <div class="field">
                            <label for="status">Statut</label>
                            <select id="status" name="status">
                                <option value="draft" <?= $statusValue === 'draft' ? 'selected' : ''; ?>>Brouillon</option>
                                <option value="published" <?= $statusValue === 'published' ? 'selected' : ''; ?>>Publié</option>
                            </select>
                        </div>
                    </div>

                    <div class="field" style="margin-top: 1rem;">
                        <label for="content">Contenu</label>
                        <textarea id="content" name="content" spellcheck="true"><?= htmlspecialchars($contentValue, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        <div class="hint">TinyMCE envoie le HTML directement dans la colonne content.</div>
                    </div>

                    <div class="grid" style="margin-top: 1rem;">
                        <div class="field">
                            <label for="image_file">Image (upload)</label>
                            <input type="file" id="image_file" name="image_file" accept="image/jpeg,image/png,image/webp,image/gif">
                            <div class="hint">Formats: JPG, PNG, WEBP, GIF. Taille max: 5 Mo.</div>
                        </div>

                        <div class="field">
                            <label>Date de publication</label>
                            <input
                                type="datetime-local"
                                id="published_at"
                                name="published_at"
                                value="<?= htmlspecialchars($publishedAtValue, ENT_QUOTES, 'UTF-8'); ?>"
                            >
                            <div class="hint">Laisser vide pour une publication immédiate.</div>
                        </div>
                    </div>

                    <div class="field" style="margin-top: 1rem;">
                        <label>SEO auto</label>
                        <div class="hint" style="margin-top: 0;">
                            Slug, URL canonique, meta title/description, meta robots, texte alt image et normalisation des titres H1..H6 sont générés automatiquement.
                        </div>
                    </div>

                    <?php if ($imageUrlValue !== ''): ?>
                        <div class="preview">
                            <div class="hint" style="margin-bottom: .45rem;">Image actuelle</div>
                            <img
                                src="<?= htmlspecialchars($imageUrlValue, ENT_QUOTES, 'UTF-8'); ?>"
                                alt="Image actuelle"
                                loading="lazy"
                                decoding="async"
                            >
                        </div>
                    <?php endif; ?>

                    <div class="actions">
                        <button type="submit"><?= htmlspecialchars($buttonLabel, ENT_QUOTES, 'UTF-8'); ?></button>
                        <a class="btn" href="<?= htmlspecialchars($dashboardUrl, ENT_QUOTES, 'UTF-8'); ?>">Retour</a>
                    </div>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
