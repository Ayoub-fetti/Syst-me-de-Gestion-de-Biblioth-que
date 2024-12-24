<?php


include 'Book.php';

$newbook = new Book("borrowed","borrowed","2","borrowed","borrowed","borrowed");
$newbook->save();

// test for displaying all books

$allbooks=$newbook->getAllBooks();

echo 'liste des livres <br>';

print_r($allbooks);


?>