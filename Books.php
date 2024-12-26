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

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php foreach ($allBooks as $book): ?>
            <div class="text-center bg-gray-100 p-4 rounded-lg shadow-md">
            <img alt="The Book of CSS3" class="w-full h-auto rounded-lg" height="300" src="<?php echo $book['cover_image']; ?>" width="200" />                
                <p name="title" class="mt-4 text-lg font-semibold">
                    <?php echo $book['title']; ?>
                </p>

                <p name="summary" class="text-gray-600 mt-2">
                    <?php echo $book['cover_image']; ?>
                </p>

                <!-- Conversion du statut -->
                <p name="status" class="mt-2 text-gray-500">
                    Status: 
                    <?php 
                        // Tableau associatif pour convertir les enums en texte
                        $statusLabels = [
                            'available' => 'Disponible',
                            'borrowed' => 'Emprunté',
                            'reserved' => 'Reservé'
                        ];

                        // Affichage du statut converti du enum status 
                        echo isset($statusLabels[$book['status']]) ? $statusLabels[$book['status']] : 'Unknown';
                    ?>
                </p>

                <!-- Bouton pour chaque card -->
                <button class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                 <?php
                 
                 // declarer cette variable pour trier la chaine de caracter remove ; w les caractere speciaux
                 $statusValue = trim($book['status']);
                 

                 // faire les tests pour afficher la button convient

                 if ($statusValue=='available'){
                 echo 'Emprunter maintenant!';
                                               }
                else                         {
                echo 'Réserver';
                                             } 
                 ?>

                </button>
            </div>
        <?php endforeach; ?>
    </div>

</body>





</html>
