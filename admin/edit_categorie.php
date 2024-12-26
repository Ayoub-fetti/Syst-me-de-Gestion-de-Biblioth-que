<?php
define('BASE_URL', 'http://localhost/votre-projet');
require_once '../connection.php';
require_once '../classes/Book.php';
require_once '../classes/User.php';
require_once '../classes/Categories.php';
require_once 'check_admin.php';

$message = '';
$categorie = new Categories("","");

// Récupérer le livre à modifier
if (isset($_GET['id'])) {
    $categorieData = $categorie->getCategoryById($_GET['id']);
    if ($categorieData) {
        $categorie = new Categories(
            $categorieData['id'],
            $categorieData['name'],
        );
        $categorie->setCategoryId($_GET['id']);
    }
}

// Récupérer les catégories
$database = new Database;
$conn = $database->connect();


if (isset($_POST['submit'])) {
    // Mettre à jour les données du livre
    $categorie = new Categories(
        $_POST['id'],
        $_POST['name'],
    );
    $categorie->setCategoryId($_GET['id']);


    // Sauvegarder les modifications
    if ($categorie->updateCategory()) {
        $message = "La categorie a été modifié avec succès!";
        // Rediriger vers la page admin avec le message
        header('Location: admin_categories.php?message=' . urlencode($message));
        exit();
    } else {
        $message = "Erreur lors de la modification du categorie.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier une Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Modifier une Categorie</h2>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Titre</label>
                <input type="text" name="name" class="form-control" 
                       value="<?php echo htmlspecialchars($categorieData['name'] ?? ''); ?>" required>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Modifier</button>
            <a href="admin_categories.php" class="btn btn-secondary">Retour</a>
        </form>
    </div>
</body>
</html>