<?php
session_start();
require_once 'connection.php';
require_once 'classes/User.php';
require_once 'classes/Book.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = $_POST['book_id'];
    $due_date = $_POST['due_date'];
    
    $database = new Database();
    $pdo = $database->connect();
    
    // Créer l'instance User avec l'ID de session
    $user = new User($pdo);
    $user->setId($_SESSION['user_id']);
    
    // Emprunter le livre
    $result = $user->borrowBook($book_id);
    
    if ($result['success']) {
        $_SESSION['borrow_message'] = "Le livre a été emprunté avec succès!";
    } else {
        $_SESSION['borrow_message'] = "Erreur: " . $result['message'];
    }
}

header('Location: books.php');
exit(); 