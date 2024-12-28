<?php
// Démarrer la mise en tampon de sortie
ob_start();

// Vérifier et nettoyer toute sortie précédente
if (ob_get_length()) ob_clean();

require_once '../connection.php';
require_once '../classes/User.php';
require_once '../classes/Book.php';
require_once 'check_admin.php';
require_once '../vendor/autoload.php'; 

// Créer une nouvelle instance de TCPDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Définir les informations du document
$pdf->SetCreator('Bibliothèque System');
$pdf->SetAuthor('Admin');
$pdf->SetTitle('Rapport des Statistiques');

// Supprimer les en-têtes et pieds de page par défaut
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Ajouter une page
$pdf->AddPage();

// Définir la police
$pdf->SetFont('helvetica', '', 12);

// Titre du rapport
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 15, 'Rapport des Statistiques de la Bibliothèque', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);

// Récupérer les statistiques
$db = new Database();
$pdo = $db->connect();
$user = new User($pdo);
$book = new Book("", "", 0, "", "", "");

$userStats = $user->getUserStatistics();
$bookStats = $book->getBookStatistics();
$mostBorrowedBooks = $book->getMostBorrowedBooks(5);
$mostActiveUsers = $user->getMostActiveUsers(5);

// Statistiques générales
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Statistiques Générales', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(0, 10, 'Total des livres: ' . $bookStats['total_books'], 0, 1, 'L');
$pdf->Cell(0, 10, 'Total des utilisateurs: ' . $userStats['total_users'], 0, 1, 'L');
$pdf->Cell(0, 10, 'Total des emprunts: ' . $bookStats['total_borrowings'], 0, 1, 'L');

// Livres les plus empruntés
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Top 5 des Livres les Plus Empruntés', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 12);

foreach ($mostBorrowedBooks as $index => $book) {
    $pdf->Cell(0, 10, ($index + 1) . '. ' . $book['title'] . ' par ' . $book['author'] . 
               ' (' . $book['borrow_count'] . ' emprunts)', 0, 1, 'L');
}

// Utilisateurs les plus actifs
$pdf->Ln(10);
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Top 5 des Utilisateurs les Plus Actifs', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 12);

foreach ($mostActiveUsers as $index => $user) {
    $pdf->Cell(0, 10, ($index + 1) . '. ' . $user['name'] . ' (' . $user['borrow_count'] . ' emprunts)', 0, 1, 'L');
}

// Vider le tampon de sortie
ob_end_clean();

// Générer le PDF
$pdf->Output('rapport_bibliotheque.pdf', 'D');
exit(); 