<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; margin: 0; }
        .container { max-width: 420px; margin: 6rem auto; background: #fff; border-radius: 10px; padding: 2rem; box-shadow: 0 8px 24px rgba(0,0,0,.08); }
        h1 { margin-top: 0; font-size: 1.4rem; }
        .error { background: #ffe8e8; color: #b00020; border: 1px solid #f5b5bf; border-radius: 8px; padding: .75rem; margin-bottom: 1rem; }
        label { display: block; margin: .7rem 0 .35rem; font-weight: 600; }
        input { width: 100%; box-sizing: border-box; padding: .65rem .7rem; border: 1px solid #d0d7de; border-radius: 8px; }
        button { margin-top: 1rem; width: 100%; padding: .7rem .9rem; border: 0; border-radius: 8px; background: #0b57d0; color: #fff; font-weight: 700; cursor: pointer; }
    </style>
</head>
<body>
    <main class="container">
        <h1>Connexion Back-Office</h1>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>

        <form method="post" action="<?= htmlspecialchars((string) $formAction, ENT_QUOTES, 'UTF-8'); ?>">
            <label for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" required autocomplete="username">

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">

            <button type="submit">Se connecter</button>
        </form>
    </main>
</body>
</html>
