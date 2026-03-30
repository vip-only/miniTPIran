# MiniTPIran - Document Technique Et Livrable

## 1) Informations Etudiant
- Nom et prenom: A COMPLETER
- Num ETU: A COMPLETER
- Promotion: A COMPLETER
- Date de rendu: 31/03/2026

## 2) Objectif Du Projet
Application web d'actualites (theme Iran) avec:
- FrontOffice (consultation articles)
- BackOffice (authentification admin + CRUD articles + upload image)
- Contraintes SEO de base (title, meta description, alt, URLs propres)

## 3) Stack Technique
- PHP (Flight PHP)
- Apache
- MySQL 8
- Docker Compose
- TinyMCE (edition contenu BO)

Fichiers principaux:
- [app/config/routes.php](app/config/routes.php)
- [app/controllers/FrontOfficeController.php](app/controllers/FrontOfficeController.php)
- [app/controllers/BackOfficeController.php](app/controllers/BackOfficeController.php)
- [app/views/home.php](app/views/home.php)
- [app/views/backoffice/article_form.php](app/views/backoffice/article_form.php)
- [sql/script.sql](sql/script.sql)
- [docker-compose.yml](docker-compose.yml)

## 4) Lancement Du Projet (Docker)
Commandes:

```bash
docker compose down -v
docker compose up -d --build
```

Acces:
- FrontOffice: http://localhost:8083/
- BackOffice login: http://localhost:8083/backoffice/login.html

Note:
- Le script SQL d'init est [sql/script.sql](sql/script.sql)
- Si la base existe deja, il faut `down -v` pour reinitialiser et relancer le seed.

## 5) Authentification BackOffice
Identifiants admin par defaut (seed):
- username: `admin`
- password: `admin123`

Reference seed admin:
- [sql/script.sql](sql/script.sql)

## 6) Modelisation Base De Donnees
Tables:
- `users`
- `articles`
- `seo_metadata`

Relations:
- `seo_metadata.article_id` -> `articles.id` (1:1)
- `ON DELETE CASCADE` sur la relation SEO

Schema logique (resume):
- `users(id, username, password_hash, role, created_at)`
- `articles(id, title, slug, content, image_url, image_alt, status, published_at, updated_at)`
- `seo_metadata(id, article_id, meta_title, meta_description, meta_robots, canonical_url)`

Reference SQL complete:
- [sql/script.sql](sql/script.sql)

## 7) Upload Image (BackOffice)
Comportement:
- L'image est envoyee depuis le formulaire BO (`multipart/form-data`)
- Le fichier est stocke sur disque dans `assets/images/uploads`
- Le chemin web est stocke dans `articles.image_url` (pas le binaire)

Emplacements:
- Machine locale: `assets/images/uploads/`
- Conteneur web: `/var/www/html/assets/images/uploads/`

References:
- [app/views/backoffice/article_form.php](app/views/backoffice/article_form.php)
- [app/controllers/BackOfficeController.php](app/controllers/BackOfficeController.php)

## 8) Routes Principales
FrontOffice:
- `GET /`
- `GET /articles/article.php`
- `GET /articles/@legacy`

BackOffice:
- `GET|POST /admin/login`
- `GET /admin`
- `GET|POST /admin/articles/create`
- `GET|POST /admin/articles/@id/edit`
- `POST /admin/articles/@id/delete`
- `GET|POST /backoffice/login.html`
- `GET|POST /backoffice/articles/create.html`
- `GET|POST /backoffice/articles/edit-@id:[0-9]+.html`
- `POST /backoffice/articles/delete-@id:[0-9]+.html`

Reference:
- [app/config/routes.php](app/config/routes.php)

## 9) Scenarios De Test (A Mettre Dans Le Document)
### Scenario 1 - Login BO
1. Ouvrir `/backoffice/login.html`
2. Saisir `admin / admin123`
3. Resultat attendu: acces dashboard admin

### Scenario 2 - Creer Article Avec Upload
1. Aller sur creation article BO
2. Renseigner titre + contenu + status `published`
3. Charger un fichier image
4. Soumettre
5. Resultat attendu: article cree, image visible, chemin en base dans `image_url`

### Scenario 3 - Modifier Article Et Remplacer Image
1. Ouvrir edition article
2. Uploader une nouvelle image
3. Sauvegarder
4. Resultat attendu: article mis a jour, ancienne image locale supprimee si upload local

### Scenario 4 - Supprimer Article
1. Supprimer un article en BO
2. Resultat attendu: ligne supprimee en base
3. Et si image locale: fichier supprime du dossier uploads

### Scenario 5 - Verification FrontOffice
1. Ouvrir `/`
2. Verifier affichage liste articles publies (ordre DESC)
3. Ouvrir un article
4. Resultat attendu: contenu + image + metadata SEO

## 10) Captures Ecran A Fournir
Checklist captures a inserer dans le document technique:
- [ ] FO - Home (liste articles)
- [ ] FO - Page article
- [ ] BO - Login
- [ ] BO - Dashboard
- [ ] BO - Form create article avec upload
- [ ] BO - Form edit article
- [ ] BO - Resultat suppression article
- [ ] BDD - table `articles` apres insertion
- [ ] BDD - table `users` (admin)
- [ ] BDD - table `seo_metadata`

Conseil nommage images de preuve:
- `capture_01_fo_home.png`
- `capture_02_fo_article.png`
- `capture_03_bo_login.png`
- etc.

## 11) Contenu Du Rendu Final (.zip)
Le .zip de rendu doit contenir au minimum:
- Code source complet
- [README.md](README.md)
- [sql/script.sql](sql/script.sql)
- [docker-compose.yml](docker-compose.yml)
- Dossier `assets/images/uploads/` (si vous voulez inclure les images deja uploadees)
- Document technique (PDF ou MD)
- Captures ecran FO/BO + modelisation BDD

## 12) Limitations Connues / Notes
- Les scripts SQL dans `/docker-entrypoint-initdb.d` ne s'executent qu'a l'initialisation d'une base vide.
- Pour rejouer le seed: `docker compose down -v` puis `docker compose up -d --build`.
- Les images uploadees doivent avoir des permissions ecriture sur `assets/images/uploads/`.

## 13) Resume De Validation
Etat attendu pour dire "projet OK pour rendu":
- [ ] Docker OK
- [ ] Login BO OK
- [ ] CRUD article OK
- [ ] Upload image OK
- [ ] FrontOffice affiche les nouveaux articles OK
- [ ] SEO de base OK
- [ ] Captures et document technique prets

