<?php

include 'connection.php';

class Book
{
    private $id;
    private $title;
    private $author;
    private $category_id;
    private $category_name;
    private $cover_image;
    private $summary;
    private $status;

    public function __construct($title, $author,$category_id, $cover_image, $summary, $status)
    {
        $this->title = $title;
        $this->author = $author;
        $this->category_id=$category_id;
        $this->cover_image = $cover_image;
        $this->summary = $summary;
        $this->status = $status;
    }

    // Fonction pour sauvegarder un livre dans la base de données
    public function save()
    {
        $database = new Database;
        $conn = $database->connect();

        if ($conn) {
            // Préparation de la requête d'insertion
            $stmt = $conn->prepare("INSERT INTO books (title, author, cover_image, summary, status) VALUES (?, ?, ?, ?, ?)");
            // Utilisation de bindValue() pour PDO
            $stmt->bindValue(1, $this->title, PDO::PARAM_STR);
            $stmt->bindValue(2, $this->author, PDO::PARAM_STR);
            $stmt->bindValue(3, $this->cover_image, PDO::PARAM_STR);
            $stmt->bindValue(4, $this->summary, PDO::PARAM_STR);
            $stmt->bindValue(5, $this->status, PDO::PARAM_STR);
            $stmt->execute();
        }
    }

    // Fonction pour récupérer tous les livres
    public function getAllBooks()
    {
        $database = new Database;
        $conn = $database->connect();

        if ($conn) {
            $stmt = $conn->query("SELECT * FROM books");
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Assurez-vous de spécifier le mode de récupération
            return $result;
        }
    }

    // Fonction pour récupérer un livre par son ID
    public function getBookById($id)
    {
        $database = new Database;
        $conn = $database->connect();

        if ($conn) {
            $stmt = $conn->prepare("SELECT * FROM books WHERE id=?");
            $stmt->bindValue(1, $id, PDO::PARAM_INT); // Liaison avec bindValue() et spécification du type
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC); // Assurez-vous de récupérer un seul résultat
            return $result;
        }
    }

    // Fonction pour modifier un livre
    public function updateBook()
    {
        $database = new Database;
        $conn = $database->connect();

        if ($conn) {
            // Préparation de la requête de mise à jour
            $stmt = $conn->prepare("UPDATE books SET title=?, author=?, cover_image=?, summary=?, status=? WHERE id=?");
            // Liaison des paramètres avec bindValue()
            $stmt->bindValue(1, $this->title, PDO::PARAM_STR);
            $stmt->bindValue(2, $this->author, PDO::PARAM_STR);
            $stmt->bindValue(3, $this->cover_image, PDO::PARAM_STR);
            $stmt->bindValue(4, $this->summary, PDO::PARAM_STR);
            $stmt->bindValue(5, $this->status, PDO::PARAM_STR);
            $stmt->bindValue(6, $this->id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    // Fonction pour supprimer un livre
    public function deleteBook()
    {
        $database = new Database;
        $conn = $database->connect();

        if ($conn) {
            // Préparation de la requête de suppression
            $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
            $stmt->bindValue(1, $this->id, PDO::PARAM_INT); // Liaison avec bindValue()
            $stmt->execute();
        }
    }

    // Getter et Setter pour l'ID
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}

?>
