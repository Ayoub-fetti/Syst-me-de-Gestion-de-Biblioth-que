<?php


include 'Book.php';

$newbook = new Book("borrowed","borrowed","2","borrowed","borrowed","borrowed");
$newbook->save();

// test for displaying all books

$allbooks=$newbook->getAllBooks();

echo 'liste des livres <br>';

if ($newbook->save()) {
    echo "Book saved successfully";
} else {
    echo "Error saving book";
}

// When displaying books
if ($allbooks) {
    echo "<pre>";
    print_r($allbooks);
    echo "</pre>";
} else {
    echo "No books found or error occurred";
}


?>