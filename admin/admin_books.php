<?php
define('BASE_URL', 'http://localhost/votre-projet');
require_once '../classes/Book.php';
require_once '../connection.php';
require_once 'check_admin.php';

// Vérification de la session admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$book = new Book("", "", 0, "", "", "");
$message = '';
if (isset($_GET['message'])) {
    $message = $_GET['message'];
}


// Get all books
$books = $book->getAllBooks();


// Handle Delete
if (isset($_POST['delete']) && isset($_POST['book_id'])) {
    $book->setId($_POST['book_id']);
    if ($book->deleteBook()) {
        $message = "Livre supprimé avec succès";
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Livres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Gestion des Livres</h2>
        
        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <a href="add_book.php" class="btn btn-primary mb-3">Ajouter un livre</a>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Catégorie</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                <tr>
                    <td><?php echo htmlspecialchars($book['id']); ?></td>
                    <td><?php echo htmlspecialchars($book['title']); ?></td>
                    <td><?php echo htmlspecialchars($book['author']); ?></td>
                    <td><?php echo htmlspecialchars($book['category_id']); ?></td>
                    <td><?php echo htmlspecialchars($book['status']); ?></td>
                    <td>
                        <a href="edit_book.php?id=<?php echo $book['id']; ?>" class="btn btn-sm btn-warning">Modifier</a>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                            <button type="submit" name="delete" class="btn btn-sm btn-danger" 
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre?')">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html> 