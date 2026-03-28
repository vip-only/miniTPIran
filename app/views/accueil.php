<style>
    /* Style général pour les cartes des animaux */
    .animal {
        padding: 30px;
        background-color: #e0f7fa;
        /* Couleur plus douce pour le fond */
        margin-left: 4%;
        border-radius: 16px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        /* Ajouter une ombre légère */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        /* Effet de survol */
        margin-bottom: 20px;
        /* Espacement entre les cartes */
    }

    /* Effet de survol pour les cartes */
    .animal:hover {
        transform: translateY(-5px);
        /* Soulever la carte */
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        /* Ombre plus marquée */
    }

    /* Style pour les noms des animaux */
    .nom {
        margin-bottom: 20px;
        text-align: center;
        font-size: 1.7rem;
        /* Augmenter la taille */
        font-weight: bold;
        color: #00796b;
        /* Couleur de texte plus douce */
    }

    .liste {
        margin-left: 8rem;
    }

    /* Style pour la section d'informations */
    .info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 1rem;
        color: #555;
    }

    .btn {
        background-color: #00796b;
        /* Couleur de fond du bouton */
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-size: 1rem;
        cursor: pointer;

        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn:hover {
        background-color: #004d40;
        /* Changer la couleur de fond au survol */
    }

    h1 i {
        color: #00796b;
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .animal {
            margin-left: 0;
            /* Retirer l'offset pour les petits écrans */
            margin-right: 0;
        }
    }
</style>
<?php
session_start();
if (!isset($_SESSION['capital'])) {
    
    $_SESSION['capital'] = $capital;
}
?>
<section>
    <header>
        <nav class="navbar">
            <ul class="logo">Capital : <?= $_SESSION['capital'] ?></ul>
            <ul class="lien">
                <li><a href="<?= base_url ?>achatAliment">Achat Aliment</a></li>
                <li><a href="<?= base_url ?>achatAnimal">acheter un animal</a></li>
                <li><a href="<?= base_url ?>ajoutCapital">ajouter capital</a></li>
            </ul>
        </nav>
    </header>
    <h1 style="text-align: center; margin-bottom: 40px;">Bienvenue dans l'<i>elevage</i></h1>
    <div class="liste row">
        <?php foreach ($animals as $animal) { ?>
            <div class="col-md-3 col-sm-6 col-xs-12 animal">
                <div class="nom"><?= $animal['nomAnimal'] ?></div>
                <div class="info">
                    <span><strong>prix:<?= $animal['prix'] ?></strong></span>
                    <span><strong><?= $animal['poids'] ?> kg</strong></span>
                    <span><strong><?= $animal['nomType'] ?></strong></span>
                </div>
            </div>
        <?php } ?>
    </div>

</section>