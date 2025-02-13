<?php
session_start();

?>
<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>
   Online Book Library
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
 </head>
 <body class="bg-white text-gray-800">
  <!-- Header -->
  <header class="bg-gray-800 text-white">
   <div class="container mx-auto flex justify-between items-center py-2 px-4">
    <div class="text-2xl font-bold">
     YouBiblio
    </div>

    </div>

   </div>
   <nav class="bg-blue-600">
    <div class="container mx-auto flex justify-between items-center py-2 px-4 text-sm">
     <a class="text-white font-semibold hover:text-gray-300 px-2" href="index.php">
      ACCEUIL
     </a>
     <a class="text-white font-semibold hover:text-gray-300 px-2" href="Books.php">
      LIVRES
     </a>
     <a class="text-white font-semibold hover:text-gray-300 px-2" href="Books.php">
      CATEGORIES
     </a>
     <a class="text-white font-semibold hover:text-gray-300 px-2" href="Books.php">
      EMPRUNTER
     </a>
     <a class="text-white font-semibold hover:text-gray-300 px-2" href="Books.php">
      RESERVER
     </a>
     <a class="text-white font-semibold hover:text-gray-300 px-2" href="#">
      CONTACT
     </a>
     <a class="text-white font-semibold hover:text-gray-300 px-2" href="register.php">
      SING UP
     </a>
     <a class="text-white font-semibold hover:text-gray-300 px-2" href="login.php">
    LOGIN 
     </a>
    </div>
   </nav>
  </header>
  <!-- Main Content -->
  <main class="container mx-auto my-4">
   <!-- Hero Section -->
   <div class="relative">
    <img alt="Library interior with bookshelves and statues" class="w-full h-96 object-cover" height="400" src="https://images.pexels.com/photos/16689057/pexels-photo-16689057/free-photo-of-livres-ecole-interieur-bibliotheque.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" width="1200"/>
    <div class="absolute inset-0 flex flex-col justify-center items-center text-white">
     <h1 class="text-4xl text-gray-100 font-bold">
      ONLINE BOOK LIBRARY
     </h1>
     <p class="text-2xl font-semibold text-gray-100">
      La meilleure plateforme pour emprunter des livres
     </p>
     <a href="Books.php" class=" font-semibold bg-blue-600 hover:bg-white hover:text-blue-600 rounded-lg px-4 py-2 mt-4">
      Découvrire nos livres
</a>
    </div>
   </div>
   <!-- Books Section -->
   <section class="mt-8">
    <h2 class="text-2xl font-bold mb-4 center text-center">
     Livres du mois
    </h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
      <?php
        // Connexion à la base de données avec PDO
        require_once 'connection.php';
        $database = new Database();
        $conn = $database->connect();
        
        try {
            // Sélectionner 5 livres aléatoires
            $query = "SELECT * FROM books ORDER BY RAND() LIMIT 5";
            $stmt = $conn->prepare($query);
            $stmt->execute();
            
            while($book = $stmt->fetch(PDO::FETCH_ASSOC)) {
              ?>
              <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img src="<?php echo htmlspecialchars($book['cover_image']); ?>" 
                     alt="<?php echo htmlspecialchars($book['title']); ?>" 
                     class="w-full h-48 object-cover">
                <div class="p-4">
                  <h3 class="font-bold text-lg mb-2"><?php echo htmlspecialchars($book['title']); ?></h3>
                  <p class="text-gray-600 text-sm mb-2"><?php echo htmlspecialchars($book['author']); ?></p>

                </div>
              </div>
              <?php
            }
        } catch(PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        
        // Fermer la connexion
        $conn = null;
      ?>
    </div>
   </section>
  </main>
 </body>
</html>
