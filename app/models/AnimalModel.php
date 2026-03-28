<?php

namespace app\models;

use Flight;

class AnimalModel {
    
   private $db;

	public function __construct($db) { 
        $this->db = $db;

	}
    public function depense($montant) {
        $db = Flight::db();
        $queryUpdate = "UPDATE E_capital SET capital = capital - ?";
        $stmtUpdate = $db->prepare($queryUpdate);
        $stmtUpdate->execute([$montant]);
    }

    public function myCapital()
    {
        $db = Flight::db();
        $query = "SELECT capital FROM E_capital";
        $stmt = $db->prepare($query);
        $stmt->execute();
        $capital = $stmt->fetchColumn();
        return $capital;
    }
    public function achatAnimal($nom, $idType, $poids , $prix , $autoVente) {
        $db = Flight::db();
        $capital = $this->myCapital();
        if ($capital > $prix) {
            $this->depense($prix);
            $query = "INSERT INTO E_animal (nomAnimal, idType, poids, dateAchat,autoVente) VALUES (?, ?, ?, now(), ?)";
            $stmt = $db->prepare($query);
            $stmt->execute([$nom, $idType, $poids,$autoVente]);
            return true;
        } else {
            return false;
        }
    }
        
    public function listerAnimal() {
        $query = "
            SELECT *
            FROM E_animal a
            INNER JOIN E_type t ON a.idType = t.idType
            WHERE a.dateMort IS NULL AND dateVente IS NULL
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function vendreAnimal($idAnimal) {
      
        $query = "SELECT a.poids, t.prix
                  FROM E_animal a
                  JOIN E_type t ON a.idType = t.idType
                  WHERE a.idAnimal = ? AND a.dateMort IS NULL AND a.dateVente IS NULL AND a.poids >= t.poidsMin";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idAnimal]);
        $animal = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($animal) {
            $montantVente = $animal['poids'] * $animal['prix'];
    
            $updateCapitalQuery = "UPDATE E_capital SET capital = capital + ? WHERE idCapital = 1"; 
            $updateCapitalStmt = $this->db->prepare($updateCapitalQuery);
            $updateCapitalStmt->execute([$montantVente]);
    
            $updateQuery = "UPDATE E_animal SET dateVente = NOW() WHERE idAnimal = ?";
            $updateStmt = $this->db->prepare($updateQuery);
            $updateStmt->execute([$idAnimal]);
    
            return true;
        } else {
            return false;
        }
    }
    public function now() {
        // Retourne la date et l'heure actuelles au format 'YYYY-MM-DD HH:MM:SS'
        return date('Y-m-d H:i:s');
    }
    public function autoVente()
    {
        foreach ($this->listerAnimal() as $animal) {
            if ($animal['autoVente'] != null) {
                if ($animal['autoVente'] > $this->now() && $animal['dateVente'] == null) {
                    $this->vendreAnimal($animal['idAnimal']);
                }
            }
        }
    }
    public function ajoutCapital($montant) {
        $db = Flight::db();
            // Ajouter au capital existant
        $queryUpdate = "UPDATE E_capital SET capital = capital + ? WHERE idCapital = (SELECT idCapital FROM E_capital ORDER BY idCapital DESC LIMIT 1)";
        $stmtUpdate = $db->prepare($queryUpdate);
        $stmtUpdate->execute([$montant]);
    }
}
?>