<?php
// Activer l'affichage des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../constants.php';
require_once '../connection.php';
require_once '../classes/User.php';

session_start();

// Vérifier si l'utilisateur est connecté et admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

try {
    // Initialiser la connexion à la base de données
    $db = new Database();
    $pdo = $db->connect();
    
    $user = new User($pdo);
    
    // Récupérer les informations de l'utilisateur à modifier
    $userId = isset($_GET['id']) ? intval($_GET['id']) : 0;
    if ($userId === 0) {
        throw new Exception("ID utilisateur non spécifié ou invalide");
    }
    
    $userToEdit = $user->getUserById($userId);
    if (!$userToEdit) {
        throw new Exception("Utilisateur non trouvé");
    }
    
    // Traitement du formulaire de modification
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['name']) || !isset($_POST['email'])) {
            throw new Exception("Données du formulaire manquantes");
        }
        
        $result = $user->updateProfile($userId, $_POST['name'], $_POST['email']);
        if ($result['success']) {
            $_SESSION['success_message'] = "Profil mis à jour avec succès";
            header('Location: admin_dashboard.php');
            exit();
        } else {
            throw new Exception($result['message']);
        }
    }
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'utilisateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Modifier l'utilisateur</h1>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <?php 
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']);
                ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Nom
                </label>
                <input type="text" id="name" name="name" 
                       value="<?php echo htmlspecialchars($userToEdit['name']); ?>"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       required>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email
                </label>
                <input type="email" id="email" name="email" 
                       value="<?php echo htmlspecialchars($userToEdit['email']); ?>"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       required>
            </div>
            
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Sauvegarder
                </button>
                <a href="admin_dashboard.php" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</body>
</html>