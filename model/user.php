<?php
class User {
    private $id;
    private $login;
    private $password;
    private $nom;
    private $email;
    private $tel;
    private $role;
    
    public function __construct($id, $login, $password, $nom, $email, $tel, $role) {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->nom = $nom;
        $this->email = $email;
        $this->tel = $tel;
        $this->role = $role;
    }
    public function insertMembre() {
        include '../connexion/connexion.php';
        // Vérifier si login ou email existe déjà
        $query = $bdd->prepare("SELECT id FROM utilisateur WHERE role=0 AND (login = ? OR email = ?)"       );
        $query->execute([$this->login, $this->email]);

        if ($query->rowCount() > 0) {
            return "exists";
        }

        // Insert
        $stmt = $bdd->prepare("
            INSERT INTO utilisateur (login, password, nom, email, tel, role) 
            VALUES (?, ?, ?, ?, ?, 0)
        ");
        $stmt->execute([
            $this->login,
           $this->password,
            $this->nom,
            $this->email,
            $this->tel
             
        ]);

        return "success";
    }

    
}
?>