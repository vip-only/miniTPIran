Voici un plan d'action structuré sous forme de To-Do List, intégrant les éléments nécessaires pour votre cahier des charges.

## ✅ To-Do List approfondie (avec critères de validation)

### 0) Cadrage rapide (à faire en premier)
- [ ] Définir l’arborescence du projet (`app/`, `public/`, `views/`, `config/`, `docker/`, `sql/`).
- [ ] Définir les conventions de nommage (routes, slugs, fichiers, classes, vues).
- [ ] Créer un board simple (Todo / Doing / Done) avec priorités **P1/P2/P3**.
- [ ] Définir le MVP (minimum livrable avant le 31/03 14h).
- **DoD :** architecture validée + backlog priorisé.

---

### 1) Base de Données (P1)
#### 1.1 Modélisation
- [ ] Rédiger MCD (entités : `articles`, `users`, `seo_metadata`).
- [ ] Rédiger MLD (types SQL, PK/FK, index, contraintes).
- [ ] Prévoir champs de traçabilité (`created_at`, `updated_at`).

#### 1.2 Schéma SQL
- [ ] Table `articles`
  - [ ] `id` (PK, AI), `title`, `slug` (UNIQUE), `content`, `published_at`, `image_alt`, `status`.
- [ ] Table `users`
  - [ ] `id`, `username` (UNIQUE), `password_hash`, `role`, `created_at`.
- [ ] Table `seo_metadata`
  - [ ] `id`, `article_id` (FK UNIQUE), `meta_title`, `meta_desc`, `canonical_url`.
- [ ] Ajouter index sur `slug`, `published_at`, `status`.
- [ ] Créer script de seed (1 admin + 3 articles d’exemple).

#### 1.3 Validation
- [ ] Tester création/suppression des tables.
- [ ] Vérifier intégrité FK + contraintes UNIQUE.
- **DoD :** `schema.sql` + `seed.sql` exécutables sans erreur.

---

### 2) Architecture technique (P1)
#### 2.1 Initialisation projet
- [ ] Initialiser Composer.
- [ ] Installer et configurer Flight PHP.
- [ ] Ajouter autoload PSR-4.

#### 2.2 Routing & Rewriting
- [ok] Configurer `.htaccess` (redirection vers `public/index.php`).
- [ok] Définir routes FrontOffice:
  - [ok] `GET /`
  - [ok] `GET /article/@slug`
- [ok] Définir routes BackOffice:
  - [ok] `GET|POST /admin/login`
  - [ok] `GET /admin`
  - [ok] `GET|POST /admin/articles/create`
  - [ok] `GET|POST /admin/articles/@id/edit`
  - [ok] `POST /admin/articles/@id/delete`

#### 2.3 Docker
- [ ] Service `php-apache`.
- [ ] Service `mysql`.
- [ ] Volumes + variables d’environnement (`DB_*`).
- [ ] Script `docker compose up --build`.
- **DoD :** projet accessible sur navigateur + DB connectée via conteneurs.

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
- **DoD :** pages valides + métadonnées dynamiques complètes.

---

### 4) Back-Office (P1)
#### 4.1 Authentification
- [ ] Login admin avec mot de passe hashé (`password_hash` / `password_verify`).
- [ ] Session sécurisée (regénération ID session après login).
- [ ] Middleware de protection routes `/admin/*`.
- [ ] Logout propre.

#### 4.2 CRUD Articles + SEO
- [ ] Dashboard : tableau des articles.
- [ ] Création article (titre, slug auto/proposé, contenu, image_alt).
- [ ] Édition article + métadonnées SEO.
- [ ] Suppression avec confirmation.
- [ ] Validation serveur:
  - [ ] slug unique
  - [ ] champs requis
  - [ ] longueur meta_desc (recommandée ~155-160).

---eto 


##### 4.2.1 Intégration WYSIWYG (TinyMCE) — SEO
- [ ] Inclure TinyMCE via CDN dans `edit_article.php`.
- [ ] Lier TinyMCE au `textarea#content`.
- [ ] Limiter les formats de blocs à : `p`, `h2`, `h3` (optionnel `h4`) pour éviter le double `<h1>`.
- [ ] Autoriser listes + liens + images, avec description image (alt) activée.
- [ ] Forcer la synchro avant submit (`tinymce.triggerSave()`).
- **Critère de validation :** l’admin structure l’article sans écrire HTML à la main, et le contenu respecte les règles SEO de base.

- **DoD :** CRUD complet fonctionnel + validations + édition TinyMCE opérationnelle.

---

### 5) Qualité, tests et sécurité (P1/P2)
- [ ] Protection CSRF sur formulaires BO.
- [ ] Requêtes SQL préparées (PDO).
- [ ] Échappement HTML en sortie (`htmlspecialchars`).
- [ ] Pages d’erreur propres (404/500).
- [ ] Jeux de tests manuels:
  - [ ] Login OK/KO
  - [ ] Création article
  - [ ] Slug dupliqué
  - [ ] Route inconnue
- **DoD :** check-list sécurité minimale validée.

---

### 6) SEO technique & performance (P2)
- [ ] Générer `sitemap.xml` (articles publiés uniquement).
- [ ] Créer `robots.txt`.
- [ ] Optimiser images (poids + dimensions).
- [ ] Ajouter cache HTTP basique (headers).
- [ ] Lighthouse mobile/desktop et correction des points critiques.
- **DoD :** score SEO Lighthouse cible ≥ 90.

---

### 7) Documentation & livraison (P1)
- [ ] Rédiger README (setup local + Docker + commandes).
- [ ] Rédiger document technique:
  - [ ] Architecture
  - [ ] Schéma DB
  - [ ] Captures FO/BO
  - [ ] Choix SEO
- [ ] Vérifier dépôt Git public propre.
- [ ] Générer ZIP final (sources + docker + scripts SQL + doc).
- **DoD :** projet clonable, lançable, documenté.

---

## ⏱️ Planning express jusqu’à la deadline (31/03 14h)

### J-3 (aujourd’hui)
- [ ] Finaliser DB + Docker + routes de base.
- [ ] Home + page article fonctionnelles.

### J-2
- [ ] BO login + CRUD complet.
- [ ] Validation serveur + sécurité minimale.

### J-1
- [ ] SEO technique (`sitemap.xml`, `robots.txt`) + Lighthouse.
- [ ] Corrections bugs + doc technique.

### Jour J (matin)
- [ ] Relecture finale.
- [ ] Push Git.
- [ ] ZIP final + vérification exécution propre.

---

## 🔍 Définition du MVP (obligatoire avant rendu)
- [ ] Front: Home + article par slug.
- [ ] Back: login + créer/éditer/supprimer article.
- [ ] SEO: title/meta description dynamiques + Hn + alt + URL propre.
- [ ] Technique: Docker fonctionnel + README + DB scriptée.
- [ ] Livraison: repo public + archive ZIP.

---

### 📄 Informations pour le Cahier des Charges

* **Projet :** Mini-projet Web Design (Mars 2026).
* **Sujet :** Site d'informations sur la guerre en Iran.
* **Technologies :** PHP (Micro-framework Flight), MySQL, Docker.
* **Contraintes SEO :** URLs propres (Rewriting), balisage sémantique (Hn), métadonnées uniques, accessibilité des images.
* **Livrables :** Dépôt Git public, Archive ZIP (conteneurs fonctionnels), Document technique.
* **Date Limite :** Mardi 31 mars à 14h00.


### 💡 Un petit conseil sur Flight PHP & Docker

Comme tu vas utiliser l’URL Rewriting, pense à activer `mod_rewrite` dans ton image Apache (Dockerfile / compose).

Active aussi les modules utiles pour le cache navigateur et la compression Gzip :

- `rewrite`
- `headers`
- `expires`
- `deflate`

Exemple Dockerfile (image Apache officielle) :

```dockerfile
RUN a2enmod rewrite headers expires deflate
```

Puis redémarre/rebuild :

```powershell
docker compose up --build -d
```

docker compose exec mysql mysql -uapp -papp mini_tp_iran