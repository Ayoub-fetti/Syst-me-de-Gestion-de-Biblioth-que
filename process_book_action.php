<?php
session_start();
require_once 'connection.php';
require_once 'classes/User.php';

// Debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = "Vous devez être connecté pour effectuer cette action.";
    header('Location: login.php');
    exit();
}

// Debug
var_dump($_POST);

// Verifier si les donnees necessaires sont presentes
if (!isset($_POST['action']) || !isset($_POST['book_id'])) {
    $_SESSION['error_message'] = "Données manquantes";
    header('Location: books.php');
    exit();
}

$db = new Database();
$pdo = $db->connect();
$user = new User($pdo);
$user->setId($_SESSION['user_id']); // Important: définir l'ID de l'utilisateur

$action = $_POST['action'];
$bookId = $_POST['book_id'];

// Debug
echo "Action: " . $action . "<br>";
echo "Book ID: " . $bookId . "<br>";

// Traiter l'action
if ($action === 'reserve') {
    if (!isset($_POST['reservation_date'])) {
        $_SESSION['error_message'] = "Date de réservation manquante";
        header('Location: books.php');
        exit();
    }
    
    $result = $user->confirmReservation($bookId, $_POST['reservation_date']);
    
    // Debug
    var_dump($result);
    
    if ($result['success']) {
        $_SESSION['success_message'] = $result['message'];
    } else {
        $_SESSION['error_message'] = $result['message'] ?? "Une erreur s'est produite";
    }
}

// Redirection
header('Location: books.php');
exit(); 