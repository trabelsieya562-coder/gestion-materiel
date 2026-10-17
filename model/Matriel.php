<?php
CLASS Matriel {
    private $id;
    private $nom;
    private $description;
    private $image;
    private $etat;
   

    public function __construct($id, $nom, $description, $image, $etat) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->image = $image;
        $this->etat = $etat;
       
    }

    
    //pour le membre
    public function getDisponible(){
    include '../connexion/connexion.php';

    $stmt = $bdd->prepare("
        SELECT *
        FROM materiel 
        where etat = 0;
    
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
//pour le admin

public function getMateriel(){
    include '../connexion/connexion.php';

    $stmt = $bdd->query("SELECT * FROM materiel");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


  public function AjouterMateriel(){
    include '../connexion/connexion.php';
  
    $check = $bdd->prepare("SELECT * FROM materiel WHERE nom = ?");
    $check->execute([$this->nom]);

    if($check->rowCount() > 0){
        return "existe";
    }

    $stmt = $bdd->prepare("
        INSERT INTO materiel (nom, description, image, etat)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([
        $this->nom,
        $this->description,
        $this->image,
        $this->etat
    ]);

    return "ok";
}
//methode pour supprimer un materiel
public function supprimerMateriel($id){
    include '../connexion/connexion.php';

    try {
        // 1. Vérifier si matériel est emprunté (état = 1(valide))
        $st = $bdd->prepare("SELECT COUNT(*) 
                             FROM emprunt 
                             WHERE id_materiel = ? AND Etat = 1");
        $st->execute([$id]);
        $count = $st->fetchColumn();

         if($count > 0){
            return [
                "status" => "error",
                "message" => "Ce matériel est actuellement emprunté"
            ];
        }
        $stmt = $bdd->prepare("DELETE FROM materiel WHERE id = ?");
        $stmt->execute([$id]);

         return [
            "status" => "success",
            "message" => "Matériel supprimé avec succès"
        ];
    } catch (Exception $e) {
        return [
            "status" => "error",
            "message" => $e->getMessage()
        ];
    }
}
//methode pour modifier un materiel
public function modifierMateriel($id){
    include '../connexion/connexion.php';

    $stmt = $bdd->prepare("
        UPDATE materiel 
        SET nom = ?, description = ?, image = ?, etat = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $this->nom,
        $this->description,
        $this->image,
        $this->etat,
        $id
    ]);

    return "ok";
}
}
?>

    

