<?php

namespace app\controllers;

use app\models\ElevageModel;
use Flight;

class ElevageController {

	public function __construct() { 

	}
    public function insertionCapital() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['capital']) && !empty($_POST['capital'])) {
                $capital = $_POST['capital'];
                Flight::elevageModel()->insertCapital($capital);
                $animals = Flight::elevageModel()->listerAnimal();
                $capital = Flight::elevageModel()->myCapital();
                
                Flight::render('template', ['page' => 'accueil','capital' => $capital,'animals' => $animals]);
            } else {
                Flight::render('template', ['page' => 'capitalForm', 'error' => 'Le capital est requis.']);
            }
        } else {
            Flight::render('template', ['page' => 'capitalForm']);
        }
    }
    public function accueil() {
        $animals = Flight::elevageModel()->listerAnimal();
        $capital = Flight::elevageModel()->myCapital();
        Flight::render('template', ['page' => 'accueil', 'animals' => $animals,'capital'=>$capital]);
    }
    public function nourrirAnimal() {
        $animals = Flight::elevageModel()->listerAnimal();
        $message = '';
    
        $tousNourris = true;
        foreach ($animals as $animal) {
            if (Flight::elevageModel()->aEteNourriAujourdHui($animal['idAnimal'])) {
                continue; // L'animal a déjà été nourri aujourd'hui, on passe au suivant
            }
    
            $nourri = Flight::elevageModel()->nourrirUnAnimal($animal['idAnimal']);
            $stockMiseAJour = Flight::elevageModel()->reduireStockAlimentation($animal['idAnimal']);
            if (!$nourri || !$stockMiseAJour) {
                $tousNourris = false;
            }
        }
    
        if ($tousNourris) {
            $message = 'Tous les animaux ont été nourris et le stock a été mis à jour.';
        } else {
            $message = 'Certains animaux n\'ont pas pu être nourris ou un problème est survenu.';
        }
    
        Flight::render('template', ['page' => 'accueil', 'animals' => $animals, 'message' => $message]);
    }
    
    public function venteAnimal() {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $idAnimal = intval($_GET['id']);

            if ($idAnimal === 0) {
                $message = "ID d'animal invalide.";
            } else {
                $result = Flight::venteModel()->vendreAnimal($idAnimal);

                if ($result) {
                    $message = "✅ L'animal (ID: $idAnimal) a été vendu avec succès.";
                } else {
                    $message = "⚠️ Impossible de vendre l'animal. Vérifiez qu'il est vivant, non vendu, et qu'il a un poids suffisant.";
                }
            }

                $animauxParType = Flight::venteModel()->listerAnimauxByType();
                Flight::render('template', ['page' => 'venteAnimal','animauxParType' => $animauxParType]);
            
        } else {
            $animauxParType = Flight::venteModel()->listerAnimauxByType();
            Flight::render('template', ['page' => 'venteAnimal','animauxParType' => $animauxParType]);
        }
    }

    public function updateTypes() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           
            $poidsMin = $_POST['poidsMin'];
            $prix = $_POST['prix'];
            $poidsMax = $_POST['poidsMax'];
            $nbrJMort = $_POST['nbrJMort'];
            $pertePoids = $_POST['pertePoids'];
            $quota = $_POST['quota']; 
    
            foreach ($poidsMin as $idType => $value) {
                Flight::venteModel()->updateAnimalType(
                    $idType, 
                    !empty($value) ? $value : null, 
                    empty($prix[$idType]) ? null : $prix[$idType], 
                    empty($poidsMax[$idType]) ? null : $poidsMax[$idType], 
                    empty($nbrJMort[$idType]) ? null : $nbrJMort[$idType], 
                    empty($pertePoids[$idType]) ? null : $pertePoids[$idType],
                    empty($quota[$idType]) ? null : $quota[$idType] 
                );
            }
    
            $types = Flight::venteModel()->getAllAnimalTypes();
            Flight::render('template', ['page' => 'types', 'types' => $types]);
        } else {
            $types = Flight::venteModel()->getAllAnimalTypes();
            Flight::render('template', ['page' => 'types', 'types' => $types]);
        }
    }
    
    public function ajouterType() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nomType = $_POST['nomType'];
            $poidsMin = $_POST['poidsMin'];
            $poidsMax = $_POST['poidsMax'];
            $prix = $_POST['prix'];
            $nbrJMort = $_POST['nbrJMort'];
            $pertePoids = $_POST['pertePoids'];
            $quota = $_POST['quota']; 
    
            $result = Flight::venteModel()->ajouterAnimalType(
                $nomType, 
                $poidsMin, 
                $poidsMax, 
                $prix, 
                $nbrJMort, 
                $pertePoids, 
                $quota 
            );
    
            if ($result) {
                $types = Flight::venteModel()->getAllAnimalTypes();
                Flight::render('template', ['page' => 'types', 'types' => $types]);
            } else {
                Flight::render('template', ['page' => 'types', 'error' => 'Erreur lors de l\'ajout du type.']);
            }
        } else {
            Flight::render('template', ['page' => 'types']);
        }
    }
    
    public function goStat()
    {
        Flight::render('template', ['page' => 'statistique']);
    }
    public function stat() {
        if (isset($_GET['date'])) {
            $date = $_GET['date'];
    
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Format de date invalide.']);
                exit;
            }
    
            $venteData = Flight::venteModel()->getVentes($date);
            $achatData = Flight::venteModel()->getAchats($date);
            $mortData  = Flight::venteModel()->getMorts($date);
            $detailsParType = Flight::venteModel()->getDetailsParType($date);
    
            foreach ($detailsParType as &$type) {
                $type['nb_vendus']        = $type['nb_vendus'] ?? 0;
                $type['revenus']          = $type['revenus'] ?? 0;
                $type['nb_achetes']       = $type['nb_achetes'] ?? 0;
                $type['estimation_vente'] = $type['estimation_vente'] ?? 0;
                $type['nb_morts']         = $type['nb_morts'] ?? 0;
                $type['perte']            = $type['perte'] ?? 0;
    
                $type['animaux'] = Flight::venteModel()->getAnimauxParTypeEtDate($type['nomType'], $date);
            }
            unset($type);
    
            $argent_obtenu     = $venteData['argent_obtenu'] ?? 0;
            $perte_financiere  = $mortData['perte_financiere'] ?? 0;
    
            $rapport_financier = ($perte_financiere > 0)
                ? round($argent_obtenu / $perte_financiere, 2)
                : ($argent_obtenu > 0 ? 'Infini' : 'N/A');
    
            $result = [
                'date'   => htmlspecialchars($date),
                'vente'  => [
                    'nb_animaux_vendus' => $venteData['nb_animaux_vendus'] ?? 0,
                    'argent_obtenu'     => $argent_obtenu
                ],
                'achat'  => [
                    'nb_animaux_achetes' => $achatData['nb_animaux_achetes'] ?? 0,
                    'estimation_revenus' => $achatData['estimation_revenus'] ?? 0  
                ],
                'mort'   => [
                    'nb_morts'         => $mortData['nb_morts'] ?? 0,
                    'perte_financiere' => $perte_financiere
                ],
                'rapport' => $rapport_financier,
                'details_par_type' => $detailsParType
            ];
    
            header('Content-Type: application/json');
            echo json_encode($result);
            exit;
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Date non spécifiée.']);
            exit;
        }
    }
    
    
    
        
}
    