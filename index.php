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
  <header class="bg-black text-white">
   <div class="container mx-auto flex justify-between items-center py-2 px-4">
    <div class="text-2xl font-bold">
     YouBiblio
    </div>
    <div class="flex items-center space-x-2">
     <input class="px-2 py-1 text-black" placeholder="Search category" type="text"/>
     <button class="bg-red-600 px-4 py-1">
      SEARCH
     </button>
    </div>

   </div>
   <nav class="bg-gray-800">
    <div class="container mx-auto flex justify-between items-center py-2 px-4 text-sm">
     <a class="text-white hover:text-blue-600 px-2" href="index.php">
      ACCEUIL
     </a>
     <a class="text-white hover:text-blue-600 px-2" href="Books.php">
      LIVRES
     </a>
     <a class="text-white hover:text-blue-600 px-2" href="Books.php">
      CATEGORIES
     </a>
     <a class="text-white hover:text-blue-600 px-2" href="Books.php">
      EMPRUNTER
     </a>
     <a class="text-white hover:text-blue-600 px-2" href="Books.php">
      RESERVER
     </a>
     <a class="text-white hover:text-blue-600 px-2" href="Contact.php">
      CONTACT
     </a>
     <a class="text-white hover:text-blue-600 px-2" href="register.php">
      SING UP
     </a>
     <a class="text-white hover:text-blue-600 px-2" href="login.php">
    LOGIN 
     </a>
    </div>
   </nav>
  </header>
  <!-- Main Content -->
  <main class="container mx-auto my-4">
   <!-- Hero Section -->
   <div class="relative">
    <img alt="Library interior with bookshelves and statues" class="w-full h-96 object-cover" height="400" src="https://storage.googleapis.com/a1aa/image/t5AouAqr8QYIKF9SyDhhiaQvnniQaC9EpvNIQevD6ffxF38nA.jpg" width="1200"/>
    <div class="absolute inset-0 flex flex-col justify-center items-center text-white">
     <h1 class="text-4xl font-bold">
      ONLINE BOOK LIBRARY
     </h1>
     <p class="text-xl">
      La meilleure plateforme pour emprunter des livres
     </p>
     <a href="Books.php" class="bg-blue-600 rounded-lg px-4 py-2 mt-4">
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
                  <a href="book_details.php?id=<?php echo htmlspecialchars($book['id']); ?>" 
                     class="bg-blue-600 text-white px-4 py-2 rounded block text-center">
                    Voir plus
                  </a>
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
