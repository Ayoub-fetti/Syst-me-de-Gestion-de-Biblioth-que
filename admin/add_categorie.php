<?php
define('BASE_URL', 'http://localhost/votre-projet');
require_once '../connection.php';
require_once '../classes/User.php';
require_once 'check_admin.php';
require_once '../classes/Categories.php';

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
        <div class="flex-1 p-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-6">Ajouter une Catégorie</h2>
                
                <?php if ($message): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nom de Catégorie</label>
                        <input type="text" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <button type="submit" name="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Ajouter</button>
                        <a href="admin_categories.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Retour</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>