<?php

include 'Book.php';
include 'connection.php';


$book = new Book("", "", "", "", "", "");
$books = $book->getAllBooks();

// Récupérer les catégories pour le formulaire
$database = new Database();
$conn = $database->connect();
$categories = $conn->query("SELECT * FROM categories")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Livres</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Gestion des Livres</h1>
        
        <!-- Bouton pour ajouter un nouveau livre -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addBookModal">
            Ajouter un livre
        </button>

        <!-- Tableau des livres -->
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
                        <button class="btn btn-sm btn-warning edit-book" data-id="<?php echo $book['id']; ?>">Modifier</button>
                        <button class="btn btn-sm btn-danger delete-book" data-id="<?php echo $book['id']; ?>">Supprimer</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Modal pour ajouter/modifier un livre -->
        <div class="modal fade" id="addBookModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter un livre</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="bookForm" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="title" class="form-label">Titre</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="author" class="form-label">Auteur</label>
                                <input type="text" class="form-control" id="author" name="author" required>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Catégorie</label>
                                <select class="form-control" id="category" name="category_id" required>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="cover_image" class="form-label">Image de couverture</label>
                                <input type="file" class="form-control" id="cover_image" name="cover_image">
                            </div>
                            <div class="mb-3">
                                <label for="summary" class="form-label">Résumé</label>
                                <textarea class="form-control" id="summary" name="summary" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Statut</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="available">Disponible</option>
                                    <option value="borrowed">Emprunté</option>
                                    <option value="reserved">Réservé</option>
                                </select>
                            </div>
                            <input type="hidden" id="book_id" name="book_id">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" id="saveBook">Enregistrer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html> 