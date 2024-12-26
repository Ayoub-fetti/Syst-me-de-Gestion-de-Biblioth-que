<?php
define('BASE_URL', 'http://localhost/votre-projet');
require_once '../connection.php';
require_once '../classes/Book.php';

$message = '';

// Récupérer les catégories
$database = new Database;
$conn = $database->connect();
$categories = [];

if ($conn) {
    try {
        $stmt = $conn->query("SELECT * FROM categories ORDER BY name");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }
}

if (isset($_POST['submit'])) {
    // Récupérer les données du formulaire
    $title = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $category_id = $_POST['category_id'] ?? 0;
    $summary = $_POST['summary'] ?? '';
    $status = $_POST['status'] ?? '';
    
    // Gestion de l'upload d'image
    $cover_image = '';
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['cover_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        

        // Vérifier si l'extension est autoriséee
        if (in_array($ext, $allowed)) {
            $cover_image = 'covers/' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['cover_image']['tmp_name'], '../' . $cover_image);
        }   // Si l'extension n'est pas autorisée
        else {
            $message = "Seuls les fichiers JPG, JPEG et PNG sont autorisés";
        }
    }

    // Créer et sauvegarder le livre
    $book = new Book($title, $author, $category_id, $cover_image, $summary, $status);
    if ($book->save()) {
        $message = "Livre ajouté avec succès";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un Livre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Ajouter un Livre</h2>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Titre</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Auteur</label>
                <input type="text" name="author" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Catégorie</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Sélectionner une catégorie</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Image de couverture</label>
                <input type="file" name="cover_image" class="form-control">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Résumé</label>
                <textarea name="summary" class="form-control" rows="3"></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="available">Disponible</option>
                    <option value="borrowed">Emprunté</option>
                </select>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Ajouter</button>
            <a href="admin_books.php" class="btn btn-secondary">Retour</a>
        </form>
    </div>
</body>
</html> 