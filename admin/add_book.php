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

  
    <title>Rapport et Statistiques</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex">
                <!-- Sidebar -->
            <div class="w-64 bg-blue-900 text-white min-h-screen">
                <div class="p-4 flex items-center">
                    <span class="ml-3 text-xl font-semibold">
                        <span class="text-blue-200">Admin</span> Dashboard
                    </span>
                </div>
                <nav class="mt-5">
                    <div class="mt-5">
                        <a class="flex items-center p-3 hover:bg-blue-800 rounded-lg" href="admin_dashboard.php">
                            <i class="fas fa-home"></i>
                            <span class="ml-3">Dashboard</span>
                        </a>
                    </div>
                    <div class="mt-5">
                        <a class="flex items-center p-3 hover:bg-blue-800 rounded-lg" href="admin_books.php">
                            <i class="fas fa-book"></i>
                            <span class="ml-3">Gestion des Livres</span>
                        </a>
                    </div>
                    <div class="mt-5">
                        <a class="flex items-center p-3 hover:bg-blue-800 rounded-lg" href="admin_categories.php">
                            <i class="fas fa-filter"></i>
                            <span class="ml-3">Gestion des categories</span>
                        </a>
                    </div>
                    <div class="mt-5">
                        <a class="flex items-center p-3 bg-blue-800 rounded-lg" href="admin_rapport.php">
                            <i class="fas fa-file-pdf"></i>
                            <span class="ml-3">statistiques et rapports</span>
                        </a>
                    </div>
                    <div class="mt-5">
                        <a href="../logout.php" class="flex items-center p-3 hover:bg-blue-800 rounded-lg text-red-500 hover:text-red-400">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="ml-3">Déconnexion</span>
                        </a>
                    </div>
                </nav>
            </div>

        <!-- Main Content -->
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
    </div>
</body>
</html>