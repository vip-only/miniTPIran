## To-Do List approfondie (avec critères de validation)

### 0) Cadrage rapide 
- [ok] Définir l’arborescence du projet (`function/`, `pages/`, `assets/`, `sql/`, `docker/`).
- [ok] Définir les conventions de nommage (routes, slugs, fichiers, vues, fonctions).
- **DoD :** architecture validée + backlog priorisé.

---

### 1) Base de Données (P1)
#### 1.1 Modélisation
- [ok] Rédiger MCD (entités : `articles`, `users`, `seo_metadata`).
- [ok] Rédiger MLD (types SQL, PK/FK, indeok, contraintes).
- [ok] Prévoir champs de traçabilité (`created_at`, `updated_at`).

#### 1.2 Schéma SQL
- [ok] Table `articles`
  - [ ] `id` (PK, AI), `title`, `slug` (UNIQUE), `content`, `published_at`, `image_alt`, `status`.
- [ok] Table `users`
  - [ ] `id`, `username` (UNIQUE), `password_hash`, `role`, `created_at`.
- [ok] Table `seo_metadata`
  - [ ] `id`, `article_id` (FK UNIQUE), `meta_title`, `meta_desc`, `canonical_url`.
- [ok] Ajouter indeok sur `slug`, `published_at`, `status`.
- [ok] Créer script de seed (1 admin + 3 articles d’eokemple).

---

### 2) Architecture technique (P1)
#### 2.1 Initialisation projet
- [ok] Initialiser Composer.
- [ok] Supprimer le framework et passer en PHP natif (routeur `index.php`).

#### 2.2 Routing & Rewriting
- [ok] Configurer `.htaccess` (front controller vers `index.php`).
- [ok] Définir routes FrontOffice:
  - [ok] `GET /`
  - [ok] `GET /article/{slug}`
- [ok] Définir routes BackOffice:
  - [ok] `GET|POST /admin/login`
  - [ok] `GET /admin`
  - [ok] `GET|POST /admin/articles/create`
  - [ok] `GET|POST /admin/articles/{id}/edit`
  - [ok] `POST /admin/articles/{id}/delete`

#### 2.3 Docker
- [ok] Service `php-apache`.
- [ok] Service `mysql`.
- [ok] Volumes + variables d’environnement (`DB_*`).
- [ok] Script `docker compose up --build`.

---

### 3) Front-Office SEO (P1)
#### 3.1 Gabarits
- [ok] Créer layout de base (header/nav/main/footer).
- [ok] Ajouter partials (`head`, `flash message`, `pagination` si besoin).

#### 3.2 Pages
- [ok] Home : liste articles publiés, tri date DESC.
- [ok] Page article via slug propre.
- [ok] Gestion 404 SEO-friendly.

#### 3.3 SEO on-page
- [ok] 1 seul `<h1>` par page.
- [ok] Hiérarchie `<h2>/<h3>` cohérente.
- [ok] `<title>` unique par page.
- [ok] `<meta name="description">` unique.
- [ok] `alt` pertinent sur toutes les images.
- [ok] URL canonique article.
- [ok] Open Graph minimum (`og:title`, `og:description`).

---

### 4) Back-Office (P1)
#### 4.1 Authentification
- [ok] Login admin avec mot de passe hashé (`password_hash` / `password_verify`).
- [ok] Session sécurisée (regénération ID session après login).
- [ok] Middleware de protection routes `/admin/*`.
- [ok] Logout propre.

#### 4.2 CRUD Articles + SEO
- [ok] Dashboard : tableau des articles.
- [ok] Création article (titre, slug auto/proposé, contenu, image_alt).
- [ok] Édition article + métadonnées SEO.
- [ok] Suppression avec confirmation.
- [ ] Validation serveur:
  - [ok] slug unique
  - [ok] champs requis
  - [ok] longueur meta_desc (recommandée ~155-160).

---eto 


##### 4.2.1 Intégration WYSIWYG (TinyMCE) — SEO
- [ok] Inclure TinyMCE via CDN dans le formulaire d’édition/création BO.
- [ok] Lier TinyMCE au `teoktarea#content`.
- [ok] Limiter les formats de blocs à : `p`, `h2`, `h3` (optionnel `h4`) pour éviter le double `<h1>`.
- [ok] Autoriser listes + liens + images, avec description image (alt) activée.
- [ok] Forcer la synchro avant submit (`tinymce.triggerSave()`).
---

### 5) Qualité, tests et sécurité (P1/P2)
- [ok] Requêtes SQL préparées (PDO).
- [ok] Échappement HTML en sortie (`htmlspecialchars`).
- [ok] Pages d’erreur propres (404/500).
- [ ] Jeux de tests manuels:
  - [ok] Login OK/KO
  - [ok] Création article
  - [ok] Slug dupliqué
  - [ok] Route inconnue

---

### 6) SEO technique & performance (P2)
- [ok] Optimiser images (poids + dimensions).
- [ok] Ajouter cache HTTP basique (headers).
- [ok] Lighthouse mobile/desktop et correction des points critiques.
- **But :** score SEO Lighthouse cible ≥ 90.

---

### 7) Documentation & livraison (P1)
- [ok] Rédiger README (setup local + Docker + commandes).
- [ok] Rédiger document technique:
  - [ok] Architecture
  - [ok] Schéma DB
  - [ok] Captures FO/BO
  - [ok] Check SEO
- [ok] Vérifier dépôt Git public propre.
- [ ] Générer ZIP final (sources + docker + scripts SQL + doc).
- [ok] Retester Docker

---

### 💡 Configuration Apache & Docker

Comme tu vas utiliser l’URL Rewriting, pense à activer `mod_rewrite` dans ton image Apache (Dockerfile / compose).

Active aussi les modules utiles pour le cache navigateur et la compression Gzip :

- `rewrite`
- `headers`
- `expires`
- `deflate`

Exemple Dockerfile (image Apache officielle) :

```dockerfile
RUN a2enmod rewrite headers eokpires deflate
```

Puis redémarre/rebuild :

```powershell
docker compose up --build -d
```

docker compose eokec mysql mysql -uapp -papp mini_tp_iran