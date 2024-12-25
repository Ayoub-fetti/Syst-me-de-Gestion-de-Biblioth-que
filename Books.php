<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>


<?php

include 'classes/Book.php';
include 'connection.php';

$book = new Book("", "", 0, "", "", "");

$allBooks=$book->getAllBooks();
//$titles= $book->getTitle();   j fais des gettes pour chaque attribut est c'est pas pratique, refaire avec $allbooks to get data for each card
//$summaries=$book->getSummaries();

?>


    
<body class="bg-white p-8">
    <h1 class="text-3xl font-bold mb-8">
        ALL BOOKS
    </h1>
    <?php foreach ($allBooks as $book):   ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">

        <div class="text-center">
            <img alt="The Book of CSS3" class="w-full h-auto" height="300" src="https://storage.googleapis.com/a1aa/image/xc2h0Gtvge0nQCxHem79mwBhMTtfmYEAkxfoccyUliJEnO6PB.jpg" width="200" />
            <p name="title" class="mt-4 text-lg">
            <?php echo $book['title']; ?>
            </p>

            <p name="summary" class="text-gray-600">
            <?php echo $book['summary']; ?>
 
            
            </p>
        </div>
    </div>
    <?php endforeach ;  ?>

</body>

</html>
