<?php
session_start();
require_once 'connection.php';
require_once 'classes/User.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit();
}

if (!isset($_GET['book_id'])) {
    echo json_encode(['success' => false, 'message' => 'ID du livre manquant']);
    exit();
}

$database = new Database();
$pdo = $database->connect();
$user = new User($pdo);
$user->setId($_SESSION['user_id']);

$result = $user->reserveBook($_GET['book_id']);
echo json_encode($result); 