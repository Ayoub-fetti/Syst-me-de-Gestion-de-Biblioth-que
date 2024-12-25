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
print_r($titles);

?>

<?php foreach ($titles as $title):   ?>
<div>
  <div class="">
    <h2 name="title" class="text-xl font-semibold"><?php echo $title['title']; ?></h2>
    <p name="summary" class="mt-2"></p>

  </div>

  <?php  endforeach     ?>
</div>



</body>


</html>
