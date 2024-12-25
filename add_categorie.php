<?php
define('BASE_URL', 'http://localhost/votre-projet');
include 'classes/Categories.php';

$message = '';


// connextion avec la base de donnes pour faire l'insertion des categories

$database = new Database;
$conn = $database->connect();


if (isset($_POST['submit'])) {
    // Récupérer les données du formulaire du categorie
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';

    // Créer et sauvegarder la categorie
    $categorie = new Categories($id,$name);
    if ($categorie->saveCategory()) {
        $message = "Categorie ajouté avec succès";
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
        <h2>Ajouter une Categorie</h2>
        
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nom de Categorie</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            
            <button type="submit" name="submit" class="btn btn-primary">Ajouter</button>
            <a href="admin_categories.php" class="btn btn-secondary">Retour</a>
        </form>
    </div>
</body>
</html> 