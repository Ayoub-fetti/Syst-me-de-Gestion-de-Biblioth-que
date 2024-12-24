<?php
include 'Book.php';

function displayTestResult($testName, $result) {
    echo "<div style='margin: 10px; padding: 10px; border: 1px solid #ccc;'>";
    echo "<strong>Test: " . $testName . "</strong><br>";
    echo "Résultat: " . ($result ? "✅ Succès" : "❌ Échec") . "<br>";
    echo "</div>";
}

// 1. Test d'ajout d'un livre
echo "<h2>1. Test d'ajout d'un livre</h2>";
$book = new Book(
    "Les Misérables",
    "Victor Hugo",
    1,
    "miserables.jpg",
    "Un chef-d'œuvre de la littérature française",
    "disponible"
);
$saveResult = $book->save();
displayTestResult("Ajout d'un livre", $saveResult);

// 2. Test de récupération de tous les livres
echo "<h2>2. Test de récupération de tous les livres</h2>";
$allBooks = $book->getAllBooks();
displayTestResult("Récupération de tous les livres", !empty($allBooks));
if ($allBooks) {
    echo "<pre>";
    print_r($allBooks);
    echo "</pre>";
}

// 3. Test de récupération d'un livre par ID
echo "<h2>3. Test de récupération d'un livre par ID</h2>";
$firstBook = $allBooks[0] ?? null;
if ($firstBook) {
    $bookById = $book->getBookById($firstBook['id']);
    displayTestResult("Récupération d'un livre par ID", !empty($bookById));
    if ($bookById) {
        echo "<pre>";
        print_r($bookById);
        echo "</pre>";
    }
}

// 4. Test de mise à jour d'un livre
echo "<h2>4. Test de mise à jour d'un livre</h2>";
if ($firstBook) {
    $book->setId($firstBook['id']);
    $book->updateBook();
    $updatedBook = $book->getBookById($firstBook['id']);
    displayTestResult("Mise à jour d'un livre", !empty($updatedBook));
}

// 5. Test de suppression d'un livre
echo "<h2>5. Test de suppression d'un livre</h2>";
if ($firstBook) {
    $book->setId($firstBook['id']);
    $book->deleteBook();
    $deletedBook = $book->getBookById($firstBook['id']);
    displayTestResult("Suppression d'un livre", empty($deletedBook));
}
?> 