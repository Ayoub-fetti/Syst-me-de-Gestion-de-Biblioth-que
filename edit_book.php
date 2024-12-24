<?php
include 'Book.php';

$message = '';
$book = new Book("", "", 0, "", "", "");

// Récupérer le livre à modifier
if (isset($_GET['id'])) {
    $bookData = $book->getBookById($_GET['id']);
    if ($bookData) {
        $book = new Book(
            $bookData['title'],
            $bookData['author'],
            $bookData['category_id'],
            $bookData['cover_image'],
            $bookData['summary'],
            $bookData['status']
        );
        $book->setId($_GET['id']);
    }
}

if (isset($_POST['submit'])) {
    // Mettre à jour les données du livre
    $book = new Book(
        $_POST['title'],
        $_POST['author'],
        $_POST['category_id'],
        $bookData['cover_image'], // Garder l'ancienne image par défaut
        $_POST['summary'],
        $_POST['status']
    );
    $book->setId($_GET['id']);

    // Gestion de la nouvelle image si fournie
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['cover_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            // Supprimer l'ancienne image si elle existe
            if (!empty($bookData['cover_image']) && file_exists($bookData['cover_image'])) {
                unlink($bookData['cover_image']);
            }
            
            $cover_image = 'covers/' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['cover_image']['tmp_name'], $cover_image);
            $book->cover_image = $cover_image;
        }
    }

    // Sauvegarder les modifications
    if ($book->updateBook()) {
        $message = "Livre modifié avec succès";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier un Livre</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Modifier un Livre</h2>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Titre</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($bookData['title']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Auteur</label>
                <input type="text" name="author" class="form-control" value="<?php echo htmlspecialchars($bookData['author']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Catégorie</label>
                <select name="category_id" class="form-control" required>
                    <option value="">Sélectionner une catégorie</option>
                    <!-- Add PHP code to populate categories -->
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Image de couverture</label>
                <?php if (!empty($bookData['cover_image'])): ?>
                    <img src="<?php echo htmlspecialchars($bookData['cover_image']); ?>" style="max-width: 200px;" class="d-block mb-2">
                <?php endif; ?>
                <input type="file" name="cover_image" class="form-control">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Résumé</label>
                <textarea name="summary" class="form-control" rows="3"><?php echo htmlspecialchars($bookData['summary']); ?></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control" required>
                    <option value="disponible" <?php echo $bookData['status'] == 'disponible' ? 'selected' : ''; ?>>Disponible</option>
                    <option value="emprunté" <?php echo $bookData['status'] == 'emprunté' ? 'selected' : ''; ?>>Emprunté</option>
                </select>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Modifier</button>
            <a href="admin_books.php" class="btn btn-secondary">Retour</a>
        </form>
    </div>
</body>
</html>