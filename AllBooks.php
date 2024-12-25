<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Books</title>
  <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>
  

<?php

include 'classes/Book.php';
include 'connection.php';

$book = new Book("", "", 0, "", "", "");

$allBooks=$book->getAllBooks();
$titles= $book->getTitle();
foreach ($allBooks as $book) {
  echo '';
}





?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
  <div class="bg-white p-6 rounded-lg shadow-md">
    <!-- Card Content -->
     <?php foreach ($allBooks as $book):   ?>
    <h2 name="title" class="text-xl font-semibold"><?php echo $book['title'];    ?></h2>
    <p name="summary" class="mt-2"></p>
    <?php  endforeach     ?>
  </div>
</div>



</body>


</html>