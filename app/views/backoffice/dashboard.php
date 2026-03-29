<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; margin: 0; }
        .container { max-width: 920px; margin: 2rem auto; background: #fff; border-radius: 10px; padding: 1.5rem; box-shadow: 0 8px 24px rgba(0,0,0,.06); }
        .top { display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
        .badge { background: #e8f0fe; color: #0b57d0; border-radius: 999px; padding: .35rem .7rem; font-size: .9rem; }
        button { padding: .55rem .9rem; border: 0; border-radius: 8px; background: #c62828; color: #fff; cursor: pointer; }
    </style>
</head>
<body>
    <main class="container">
        <div class="top">
            <h1>Dashboard Back-Office</h1>
            <form method="post" action="<?= htmlspecialchars((string) $logoutAction, ENT_QUOTES, 'UTF-8'); ?>">
                <button type="submit">Se déconnecter</button>
            </form>
        </div>

        <p>Connecté en tant que <span class="badge"><?= htmlspecialchars((string) $username, ENT_QUOTES, 'UTF-8'); ?></span></p>
        <p>Routes protégées : <strong>/admin/*</strong></p>
    </main>
</body>
</html>
