<?php
require_once '../connection.php';
require_once '../classes/User.php';

session_start();

// Vérifier si l'utilisateur est connecté et admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Initialiser la connexion à la base de données
$db = new Database();
$pdo = $db->connect();

if (isset($_POST['id'])) {
    $user = new User($pdo);  
    
    // Vérifier que l'utilisateur existe et n'est pas admin
    $userToDelete = $user->getUserById($_POST['id']);
    if ($userToDelete && $userToDelete['role'] !== 'admin') {
        $result = $user->deleteUser($_POST['id']);
        
        if ($result['success']) {
            $_SESSION['success_message'] = "Utilisateur supprimé avec succès";
        } else {
            $_SESSION['error_message'] = $result['message'];
        }
    } else {
        $_SESSION['error_message'] = "Impossible de supprimer cet utilisateur";
    }
}

header('Location: admin_dashboard.php');
exit();