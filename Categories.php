<?php


include 'connection.php';


class Categories {
    private $id;
    private $name;

public function __construct($name){
    $this->name=$name;
}

public function getNameCategory(){
    return $this->name;
}

public function setCategoryName($name){
    $this->name = $name;
}


public function getAllCategories()
{
    $database=new Database;
    $conn=$database->connect();

    if ($conn) {
        $stmt = $conn->query("SELECT * FROM categories");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        return $result;
    }

}



public function getCategoryById(){
    $database = new Database;
    $conn = $database->connect();

    if ($conn) {
        $stmt = $conn->prepare("SELECT * FROM categories WHERE id=?");
        $stmt->bindValue(1, $id, PDO::PARAM_INT); // Liaison avec bindValue() et spécification du type
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC); 
        return $result;
    }
      }


public function updateCategory(){
    $database = new Database;
    $conn = $database->connect();
    if($conn){
        try{
            $stmt = $conn->prepare("UPDATE categories SET name=? WHERE id=?");
            $stmt->bindValue(1, $this->name, PDO::PARAM_STR);
            $stmt->bindValue(2, $this->id, PDO::PARAM_INT);
            return $stmt->execute();
        }catch(PDOException $e){
            echo "Erreur: " . $e->getMessage();
            return false;
        }
    }
}

public function deleteCategory()
{
    $database = new Database;
    $conn = $database->connect();

    if ($conn) {
        try {
            $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->bindValue(1, $this->id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression: " . $e->getMessage();
            return false;
        }
    }
    return true;
}

}

?>