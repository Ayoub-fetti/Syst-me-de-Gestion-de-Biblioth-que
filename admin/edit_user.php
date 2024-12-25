<?php
require_once 'connection.php';
require_once 'classes/User.php';

session_start();

// Vérifier si l'utilisateur est connecté et admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Initialiser la connexion à la base de données
$db = new Database();
$pdo = $db->connect();

$user = new User($pdo);

// Récupérer les informations de l'utilisateur à modifier
$userId = $_GET['id'] ?? null;
if (!$userId) {
    header('Location: admin_dashboard.php');
    exit();
}

$userToEdit = $user->getUserById($userId);

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $user->updateProfile($userId, $_POST['name'], $_POST['email']);
    
    if ($result['success']) {
        $_SESSION['success_message'] = "Profil mis à jour avec succès";
        header('Location: admin_dashboard.php');
        exit();
    } else {
        $_SESSION['error_message'] = $result['message'];
    }
}
?>

<!DOCTYPE html>
<html>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/index.php">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Modifier l'utilisateur</h1>
        
        <form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                    Nom
                </label>
                <input type="text" id="name" name="name" 
                       value="<?php echo htmlspecialchars($userToEdit['name']); ?>"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email
                </label>
                <input type="email" id="email" name="email" 
                       value="<?php echo htmlspecialchars($userToEdit['email']); ?>"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
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