<?php
session_start();
require_once 'connection.php';
require_once 'classes/User.php';


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verifier si les donnees necessaires sont presentes
if (!isset($_POST['action']) || !isset($_POST['book_id'])) {
    $_SESSION['error_message'] = "Données manquantes";
    header('Location: Books.php');
    exit();
}

$db = new Database();
$pdo = $db->connect();
$user = new User($pdo);

$action = $_POST['action'];
$bookId = $_POST['book_id'];

// Traiter l'action
$result = [];
if ($action === 'borrow') {
    $result = $user->borrowBook($bookId);
} elseif ($action === 'reserve') {
    $result = $user->reserveBook($bookId);
}

// Rediriger avec un message
if ($result['success']) {
    $_SESSION['success_message'] = $result['message'];
} else {
    $_SESSION['error_message'] = $result['message'];
}

header('Location: Books.php');
exit(); 