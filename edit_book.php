<?php

include 'Book.php';
include 'connection.php';


// Récupérer le livre à modifier
$bookId = $_GET['id'] ?? null;
if (!$bookId) {
    header('Location: manage_books.php');
    exit();
}

$book = new Book("", "", "", "", "", "");
$bookData = $book->getBookById($bookId);

// Récupérer les catégories
$database = new Database();
$conn = $database->connect();
$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);

// Traiter le formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updatedBook = new Book(
        $_POST['title'],
        $_POST['author'],
        $_POST['category_id'],
        $_FILES['cover_image']['name'] ?: $bookData['cover_image'],
        $_POST['summary'],
        $_POST['status']
    );
    $updatedBook->setId($bookId);
    
    if ($updatedBook->updateBook()) {
        header('Location: manage_books.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un livre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Modifier un livre</h1>
        
        <div class="card">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">Titre</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?php echo htmlspecialchars($bookData['title']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="author" class="form-label">Auteur</label>
                        <input type="text" class="form-control" id="author" name="author" 
                               value="<?php echo htmlspecialchars($bookData['author']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label">Catégorie</label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" 
                                        <?php echo $category['id'] == $bookData['category_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="cover_image" class="form-label">Image de couverture</label>
                        <input type="file" class="form-control" id="cover_image" name="cover_image">
                        <?php if ($bookData['cover_image']): ?>
                            <small class="form-text text-muted">Image actuelle : <?php echo htmlspecialchars($bookData['cover_image']); ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="summary" class="form-label">Résumé</label>
                        <textarea class="form-control" id="summary" name="summary" rows="3"><?php echo htmlspecialchars($bookData['summary']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">Statut</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="available" <?php echo $bookData['status'] == 'available' ? 'selected' : ''; ?>>Disponible</option>
                            <option value="borrowed" <?php echo $bookData['status'] == 'borrowed' ? 'selected' : ''; ?>>Emprunté</option>
                            <option value="reserved" <?php echo $bookData['status'] == 'reserved' ? 'selected' : ''; ?>>Réservé</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    <a href="manage_books.php" class="btn btn-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 