<?php
// db.php : Connexion à la base de données

class Database {
    private $host = 'localhost';   
    private $dbname = 'Bibliotheque';   // Nom de la base de données
    private $username = 'root';    // Nom d'utilisateur pour la base de données
    private $password = '';        // Mot de passe pour la base de données
    private $conn;

    public function connect() {
        try {
            // Crée une nouvelle connexion PDO
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;  // Retourne la connexion
        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
            return null;
        }
    }
}
?>
