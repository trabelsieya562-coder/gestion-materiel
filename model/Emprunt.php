<?php
 class Emprunt {
    private $id;
    private $dateDeman;
    private $dateEmprun;
    private $nbJoursDemand;
    private $dateRetou;
    private $nbJoursRetar;
    private $montantPenalit;
    private $Etat;
    private $id_materiel;
    private $id_membr;
   

    public function __construct($id, $dateDeman,  $dateEmprun, $nbJoursDemand, $dateRetou, $nbJoursRetar, $montantPenalit, $Etat, $id_materiel, $id_membr   ) {
        $this->id = $id;
        $this->dateDeman = $dateDeman;
        $this->dateEmprun = $dateEmprun;
        $this->nbJoursDemand = $nbJoursDemand;
        $this->dateRetou = $dateRetou;
        $this->nbJoursRetar = $nbJoursRetar;
        $this->montantPenalit = $montantPenalit;
        $this->Etat = $Etat;
        $this->id_materiel = $id_materiel;
        $this->id_membr = $id_membr;
    }
    //methode pour le gestionnaire
    public function getEnAttente(): array {
    include '../connexion/connexion.php'; 

    $s = $bdd->prepare("
        SELECT e.*, m.nom AS nom_materiel, u.nom AS nom_membre
        FROM emprunt e
        JOIN materiel m ON e.id_materiel = m.id
        JOIN utilisateur u ON e.id_membr = u.id
        WHERE e.Etat = 0 
        ORDER BY e.dateDeman ASC
    ");

    $s->execute();
    return $s->fetchAll(PDO::FETCH_ASSOC);
}
//cette methode pour l'adminstrateur   
//retourne tous les emprunts de tous  les membres  du ancien au plus récent
public function getTous(): array {
    include '../connexion/connexion.php'; 

    $s = $bdd->prepare("
        SELECT e.*, m.nom AS nom_materiel, u.nom AS nom_membre
        FROM emprunt e
        JOIN materiel m ON e.id_materiel = m.id
        JOIN utilisateur u ON e.id_membr = u.id
        ORDER BY e.dateDeman ASC
    ");
    $s->execute();
    return $s->fetchAll(PDO::FETCH_ASSOC);
}

public function getValides(): array {
    include '../connexion/connexion.php';
        $s = $bdd->prepare("
            SELECT e.*, m.nom AS nom_materiel, u.nom AS nom_membre
            FROM emprunt e
            JOIN materiel m ON e.id_materiel=m.id
            JOIN utilisateur u ON e.id_membr=u.id
            WHERE e.Etat=1 ORDER BY e.dateEmprun ASC");
        $s->execute(); return $s->fetchAll();
    }   

public function valider($id) {

    include '../connexion/connexion.php';

    //  get emprunt
    $stmt = $bdd->prepare("
        SELECT id_membr, id_materiel 
        FROM emprunt 
        WHERE id = ?
    ");
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) return false;

    $id_membre = $data['id_membr'];

    
    $stmt = $bdd->prepare("
        SELECT COUNT(*) 
        FROM emprunt 
        WHERE id_membr = ? 
        AND Etat = 1
    ");
    $stmt->execute([$id_membre]);
    $nb = $stmt->fetchColumn();

    //max de nombre d'emprunts valides pour un membre
    if ($nb >= 2) {
        return 1; // limite atteinte
    }

    // 4️⃣ update materiel
    $stmt = $bdd->prepare("
        UPDATE materiel 
        SET etat = 1 
        WHERE id = ? 
    ");
    $stmt->execute([$data['id_materiel']]);

     if ($stmt->rowCount() == 0) {
        return 2; // matériel déjà emprunté
    }
    //update etat emprunt
    $stmt = $bdd->prepare("
        UPDATE emprunt 
        SET Etat = 1, dateEmprun = CURDATE()
        WHERE id = ?
    ");
    $stmt->execute([$id]);
        return 0; //SUCCES
}

public function refuser($id) {
    include '../connexion/connexion.php';
    $stmt= $bdd->prepare("
        UPDATE emprunt 
        SET Etat = 2 
        WHERE id = ?
    ");
    $stmt->execute([$id]);
}
public  static  function cloturerEmprunt($id, $retard, $penalite)
{
    include '../connexion/connexion.php';


    $stmt = $bdd->prepare("SELECT id_materiel FROM emprunt WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $data = $stmt->fetch();
    $id_materiel = $data['id_materiel'];

    $sql = "UPDATE emprunt 
            SET Etat = 3,
                nbJoursRetar = :retard,
                montantPenalit = :penalite
            WHERE id = :id";

    $stmt = $bdd->prepare($sql);

    $stmt->bindParam(':retard', $retard);
    $stmt->bindParam(':penalite', $penalite);
    $stmt->bindParam(':id', $id);

    $stmt->execute();
    // update materiel 
    $stmt2 = $bdd->prepare("UPDATE materiel SET etat = 0 WHERE id = :id_materiel");
    $stmt2->bindParam(':id_materiel', $id_materiel);
    $stmt2->execute();
}
public static function getById($id)
{
   include '../connexion/connexion.php';

    $sql = "SELECT * FROM emprunt WHERE id = :id";

    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    $emprunt = $stmt->fetch(PDO::FETCH_ASSOC);

    return $emprunt;
}

    // pour le membre retourne les emprunts d'un seul membre resultat filtreee
  public function getALL(){
    include '../connexion/connexion.php';

    $stmt = $bdd->prepare("
        SELECT 
           e.dateDeman,
           e.dateEmprun,
            e.dateRetou,
            e.nbJoursDemand,
            e.nbJoursRetar,
            e.montantPenalit,
            e.Etat,
            m.nom AS nom_materiel
        FROM emprunt e 
        JOIN materiel m ON e.id_materiel=m.id
        WHERE id_membr = ?
    ");

    $stmt->execute([$this->id_membr]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function rendre($id){ 
    include '../connexion/connexion.php';   

    $stmt = $bdd->prepare("
        UPDATE emprunt
        SET 
            Etat = 3,
            nbJoursRetar = DATEDIFF(CURDATE(), date_Retour),
            montantPenalit = 
                CASE 
                    WHEN DATEDIFF(CURDATE(), date_Retour) > 0 
                    THEN DATEDIFF(CURDATE(), date_Retour) * 3
                    ELSE 0
                END
        WHERE id = ?
    ");

    $stmt->execute([$id]);
}
function supprimerEmprunt($id){
    include '../connexion/connexion.php';

    $stmt = $bdd->prepare("DELETE FROM emprunt WHERE id = ?");
    $stmt->execute([$id]);
 }
 function modifierEmprunt($id, $dateDeman, $nbJoursDemand){
    include '../connexion/connexion.php';

    $stmt = $bdd->prepare("
        UPDATE emprunt 
        SET dateEmprun; = ?, nbJoursDemand = ? 
        WHERE id = ?
    ");

    $stmt->execute([$dateEmprun, $nbJoursDemand, $id]);
}

 }
 ?>
 