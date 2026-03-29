<?php
$mode = $mode ?? 'create';
$article = is_array($article ?? null) ? $article : [];
$titleValue = (string) ($article['title'] ?? '');
$contentValue = (string) ($article['content'] ?? '');
$imageUrlValue = (string) ($article['image_url'] ?? '');
$imageAltValue = (string) ($article['image_alt'] ?? '');
$statusValue = (string) ($article['status'] ?? 'draft');
$metaTitleValue = (string) ($article['meta_title'] ?? '');
$metaDescriptionValue = (string) ($article['meta_description'] ?? '');
$metaRobotsValue = (string) ($article['meta_robots'] ?? 'index, follow');
$canonicalUrlValue = (string) ($article['canonical_url'] ?? '');
$publishedAtValue = (string) ($article['published_at'] ?? '');
$formAction = (string) ($formAction ?? '/backoffice/articles/create.html');
$pageTitle = $mode === 'edit' ? 'Modifier un article' : 'Créer un article';
$buttonLabel = $mode === 'edit' ? 'Mettre à jour' : 'Créer';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#content',
            plugins: 'lists link image table code help wordcount',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
            block_formats: 'Paragraphe=p; Titre 2=h2; Titre 3=h3; Titre 4=h4',
            image_description: true,
            image_advtab: true,
            setup: function (editor) {
                editor.on('change', function () {
                    tinymce.triggerSave();
                });
            }
        });
    </script>
    <style>
        body { font-family: Arial, sans-serif; background: #f6f8fb; margin: 0; }
        .container { max-width: 1100px; margin: 2rem auto; background: #fff; padding: 1.5rem; border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,.06); }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        label { display: block; font-weight: 600; margin: .8rem 0 .35rem; }
        input, textarea, select { width: 100%; box-sizing: border-box; padding: .65rem .75rem; border: 1px solid #d0d7de; border-radius: 8px; }
        textarea { min-height: 140px; }
        .actions { display: flex; gap: .75rem; margin-top: 1.2rem; }
        button, a.btn { padding: .7rem 1rem; border: 0; border-radius: 8px; text-decoration: none; font-weight: 700; }
        button { background: #0b57d0; color: #fff; cursor: pointer; }
        a.btn { background: #e9eef6; color: #1f2937; }
        .error { background: #ffe8e8; color: #b00020; border: 1px solid #f5b5bf; border-radius: 8px; padding: .75rem; margin-bottom: 1rem; }
        .info { background: #eaf6ea; color: #1f7a1f; border: 1px solid #bfe5bf; border-radius: 8px; padding: .75rem; margin-bottom: 1rem; }
        .hint { color: #6b7280; font-size: .92rem; }
    </style>
</head>
<body>
    <main class="container">
        <h1><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></h1>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <?php if (!empty($info)): ?>
            <div class="info"><?= htmlspecialchars((string) $info, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form method="post" action="<?= htmlspecialchars($formAction, ENT_QUOTES, 'UTF-8'); ?>">
            <div class="grid">
                <div>
                    <label for="title">Titre</label>
                    <input type="text" id="title" name="title" value="<?= htmlspecialchars($titleValue, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div>
                    <label for="status">Statut</label>
                    <select id="status" name="status">
                        <option value="draft" <?= $statusValue === 'draft' ? 'selected' : ''; ?>>Brouillon</option>
                        <option value="published" <?= $statusValue === 'published' ? 'selected' : ''; ?>>Publié</option>
                    </select>
                </div>
            </div>

            <label for="content">Contenu</label>
            <textarea id="content" name="content"><?= htmlspecialchars($contentValue, ENT_QUOTES, 'UTF-8'); ?></textarea>
            <div class="hint">TinyMCE envoie le HTML directement dans la colonne content.</div>

            <div class="grid">
                <div>
                    <label for="image_url">Image URL</label>
                    <input type="text" id="image_url" name="image_url" value="<?= htmlspecialchars($imageUrlValue, ENT_QUOTES, 'UTF-8'); ?>">
                </div>

                <div>
                    <label for="image_alt">Image alt</label>
                    <input type="text" id="image_alt" name="image_alt" value="<?= htmlspecialchars($imageAltValue, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
            </div>

            <div class="grid">
                <div>
                    <label for="published_at">Date de publication</label>
                    <input type="text" id="published_at" name="published_at" value="<?= htmlspecialchars($publishedAtValue, ENT_QUOTES, 'UTF-8'); ?>" placeholder="YYYY-MM-DD HH:MM:SS">
                </div>

                <div>
                    <label for="canonical_url">Canonical URL</label>
                    <input type="text" id="canonical_url" name="canonical_url" value="<?= htmlspecialchars($canonicalUrlValue, ENT_QUOTES, 'UTF-8'); ?>" placeholder="/article/slug">
                </div>
            </div>

            <div class="grid">
                <div>
                    <label for="meta_title">Meta title</label>
                    <input type="text" id="meta_title" name="meta_title" value="<?= htmlspecialchars($metaTitleValue, ENT_QUOTES, 'UTF-8'); ?>">
                </div>

                <div>
                    <label for="meta_robots">Meta robots</label>
                    <input type="text" id="meta_robots" name="meta_robots" value="<?= htmlspecialchars($metaRobotsValue, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
            </div>

            <label for="meta_description">Meta description</label>
            <textarea id="meta_description" name="meta_description"><?= htmlspecialchars($metaDescriptionValue, ENT_QUOTES, 'UTF-8'); ?></textarea>

            <div class="actions">
                <button type="submit"><?= htmlspecialchars($buttonLabel, ENT_QUOTES, 'UTF-8'); ?></button>
                <a class="btn" href="/backoffice.html">Retour</a>
            </div>
        </form>
    </main>
</body>
</html>
