<?php

// include '../connection.php';
require_once dirname(__DIR__) . '/connection.php';

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
            try {
                $stmt = $conn->prepare("INSERT INTO books (title, author, category_id, cover_image, summary, status) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bindValue(1, $this->title, PDO::PARAM_STR);
                $stmt->bindValue(2, $this->author, PDO::PARAM_STR);
                $stmt->bindValue(3, $this->category_id, PDO::PARAM_INT);
                $stmt->bindValue(4, $this->cover_image, PDO::PARAM_STR);
                $stmt->bindValue(5, $this->summary, PDO::PARAM_STR);
                $stmt->bindValue(6, $this->status, PDO::PARAM_STR);
                return $stmt->execute();
            } catch (PDOException $e) {
                echo "Erreur: " . $e->getMessage();
                return false;
            }
        }
        return false;
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
            $result = $stmt->fetch(PDO::FETCH_ASSOC); 
            return $result;
        }
    }

    // Fonction pour modifier un livre
    public function updateBook()
    {
        $database = new Database;
        $conn = $database->connect();

        if ($conn) {
            try {
                // Préparation de la requête de mise à jour
                $stmt = $conn->prepare("UPDATE books SET title=?, author=?, category_id=?, cover_image=?, summary=?, status=? WHERE id=?");
                // Liaison des paramètres avec bindValue()
                $stmt->bindValue(1, $this->title, PDO::PARAM_STR);
                $stmt->bindValue(2, $this->author, PDO::PARAM_STR);
                $stmt->bindValue(3, $this->category_id, PDO::PARAM_INT);
                $stmt->bindValue(4, $this->cover_image, PDO::PARAM_STR);
                $stmt->bindValue(5, $this->summary, PDO::PARAM_STR);
                $stmt->bindValue(6, $this->status, PDO::PARAM_STR);
                $stmt->bindValue(7, $this->id, PDO::PARAM_INT);
                
                return $stmt->execute();
            } catch (PDOException $e) {
                echo "Erreur lors de la mise à jour: " . $e->getMessage();
                return false;
            }
        }
        return false;
    }

    // Fonction pour supprimer un livre
    public function deleteBook()
    {
        $database = new Database;
        $conn = $database->connect();

        if ($conn) {
            try {
                $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
                $stmt->bindValue(1, $this->id, PDO::PARAM_INT);
                return $stmt->execute();
            } catch (PDOException $e) {
                echo "Erreur lors de la suppression: " . $e->getMessage();
                return false;
            }
        }
        return true;
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

    // Getter pour cover_image
    public function getCoverImage() {
        return $this->cover_image;
    }

    // Setter pour cover_image
    public function setCoverImage($cover_image) {
        $this->cover_image = $cover_image;
    }


    public function searchBooks($searchTerm) {
        $database = new Database;
        $conn = $database->connect();

        if ($conn) {
            try {
                $searchTerm = "%$searchTerm%";
                $query = "SELECT * FROM books WHERE title LIKE :search OR author LIKE :search";
                
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':search', $searchTerm);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                echo "Erreur: " . $e->getMessage();
                return [];
            }
        }
        return [];
    }

    public function getAllCategories() {
        $database = new Database;
        $conn = $database->connect();

        if ($conn) {
            try {
                $stmt = $conn->query("SELECT * FROM categories");
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                echo "Erreur: " . $e->getMessage();
                return [];
            }
        }
        return [];
    }

    public function getBooksByCategory($categoryId) {
        $database = new Database;
        $conn = $database->connect();

        if ($conn) {
            try {
                if (empty($categoryId)) {
                    // Si aucune catégorie n'est sélectionnée, retourner tous les livres
                    return $this->getAllBooks();
                }

                $stmt = $conn->prepare("SELECT * FROM books WHERE category_id = ?");
                $stmt->bindValue(1, $categoryId, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch(PDOException $e) {
                echo "Erreur: " . $e->getMessage();
                return [];
            }
        }
        return [];
    }

}

?>
