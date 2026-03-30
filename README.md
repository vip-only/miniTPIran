# MiniTPIran - Version PHP Sans Framework

Ce projet a ete migre de Flight vers une architecture PHP simple, en conservant les fonctionnalites FrontOffice et BackOffice demandees dans [Afaire.md](Afaire.md).

## Architecture
- [index.php](index.php): routeur natif (remplace routes/controllers Flight)
- [function/common.php](function/common.php): session, PDO, rendu
- [function/function_frontOffice.php](function/function_frontOffice.php): logique FrontOffice
- [function/function_backoffice.php](function/function_backoffice.php): logique BackOffice
- [pages/](pages): vues FO/BO
- [assets/](assets): css, images, uploads

## Fonctionnalites conservees
- FrontOffice:
  - Home (articles `published` tri DESC)
  - Article legacy: `/articles/article-{id}-{page}-{rubrique}.html`
  - Article slug: `/article/{slug}`
  - 404 SEO
- BackOffice:
  - Login/logout admin
  - Dashboard articles
  - Create/edit/delete article
  - Upload image local vers `assets/images/uploads/`
  - Meta SEO (title/description/robots/canonical)

## Routes prises en charge (sans framework)
- `GET /`
- `GET /article/{slug}`
- `GET /articles/article-{id}-{page}-{rubrique}.html`
- `GET /articles/article.php?id={id}&page={page}&rubrique={rubrique}`
- `GET|POST /admin/login`
- `POST /admin/logout`
- `GET /admin`
- `GET|POST /admin/articles/create`
- `GET|POST /admin/articles/{id}/edit`
- `POST /admin/articles/{id}/delete`
- `GET|POST /backoffice/login.html`
- `POST /backoffice/logout.html`
- `GET /backoffice.html`
- `GET|POST /backoffice/articles/create.html`
- `GET|POST /backoffice/articles/edit-{id}.html`
- `POST /backoffice/articles/delete-{id}.html`

## Base de donnees
Script SQL principal:
- [sql/script.sql](sql/script.sql)

Identifiants admin seed:
- username: `admin`
- password: `admin123`

## Upload images
- Stockage physique: `assets/images/uploads/`
- Valeur stockee en base (`articles.image_url`): `/assets/images/uploads/<fichier>`
- Types acceptes: jpg, png, webp, gif
- Taille max: 5 Mo

## .htaccess
Le fichier [\.htaccess](.htaccess) est configure en front controller:
- les fichiers/dossiers existants sont servis directement
- toutes les autres URLs passent par [index.php](index.php)

## Lancement Docker
```bash
docker compose down -v
docker compose up -d --build
```

URLs:
- FrontOffice: http://localhost:8083/
- BackOffice login: http://localhost:8083/backoffice/login.html

## Verification rapide (scenarios)
1. Login BO: `admin / admin123`
2. Create article + upload image
3. Edit article + remplacement image
4. Delete article
5. Verification FO home + article

## Notes migration
- Controllers Flight supprimes
- Fichier `app/config/routes.php` supprime
- Routage et logique de controle deplacees dans `function/` + `index.php`
