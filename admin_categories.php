<?php
include 'Categories.php';

$categorie = new Categories("","");
$message = '';
if (isset($_GET['message'])) {
    $message = $_GET['message'];
}


// Get all books
$categories = $categorie->getAllCategories();


// Handle Delete
if (isset($_POST['delete']) && isset($_POST['categorie_id'])) {
    $categorie->setCategoryId($_POST['categorie_id']);
    if ($categorie->deleteCategory()) {
        $message = "Categorie supprimé avec succès";
        header('Location: admin_categories.php?message=' . urlencode($message));
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

        <a href="add_categorie.php" class="btn btn-primary mb-3">Ajouter un livre</a>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Categorie</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $categorie): ?>
                <tr>
                    <td><?php echo htmlspecialchars($categorie['id']); ?></td>
                    <td><?php echo htmlspecialchars($categorie['name']); ?></td>
                    <td>
                        <a href="edit_categorie.php?id=<?php echo $categorie['id']; ?>" class="btn btn-sm btn-warning">Modifier</a>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="categorie_id" value="<?php echo $categorie['id']; ?>">
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