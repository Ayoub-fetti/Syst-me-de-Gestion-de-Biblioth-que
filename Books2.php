<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<?php
include 'classes/Book.php';
include 'connection.php';

$book = new Book("", "", 0, "", "", "");
$allBooks = $book->getAllBooks();
?>

<body class="bg-white p-8">

    <!-- Barre de recherche -->
    <form id="searchForm" method="post" action="">
        <input type="text" id="searchInput" name="keywords" placeholder="Search for books" class="border p-2 rounded">
    </form>

    <div class="flex gap-4 mb-4">
        <select id="categoryFilter" class="border p-2 rounded">
            <option value="">Toutes les catégories</option>
            <?php
            // Créez une instance de votre classe Category ou utilisez une méthode de Book
            $categories = $book->getAllCategories(); // Vous devrez créer cette méthode
            foreach($categories as $category) {
                echo "<option value='" . $category['id'] . "'>" . htmlspecialchars($category['name']) . "</option>";
            }
            ?>
        </select>
    </div>

    <h1 class="text-3xl font-bold mb-8">ALL BOOKS</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8" id="booksContainer">
        <?php foreach ($allBooks as $book): ?>
            <div class="text-center bg-gray-100 p-4 rounded-lg shadow-md">
                <img alt="The Book of CSS3" class="w-full h-auto rounded-lg" height="300" src="<?php echo $book['cover_image']; ?>" width="200" />
                <p name="title" class="mt-4 text-lg font-semibold"><?php echo $book['title']; ?></p>
                <p name="summary" class="text-gray-600 mt-2"><?php echo $book['summary']; ?></p>
                <p name="status" class="mt-2 text-gray-500">Status: 
                    <?php 
                    $statusLabels = [
                        'available' => 'Disponible',
                        'borrowed' => 'Emprunté',
                        'reserved' => 'Reservé'
                    ];
                    echo isset($statusLabels[$book['status']]) ? $statusLabels[$book['status']] : 'Unknown';
                    ?>
                </p>
                <button class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    <?php
                    $statusValue = trim($book['status']);
                    echo $statusValue == 'available' ? 'Emprunter maintenant!' : 'Réserver';
                    ?>
                </button>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="result"></div>

    <script>
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                const query = $(this).val();
                if (query.length >= 2) {
                    $.ajax({
                        url: 'search_books.php', // Assurez-vous que ce fichier existe et est configuré
                        method: 'POST',
                        data: { query: query },
                        success: function(data) {
                            $('#booksContainer').html(data);
                        },
                        error: function() {
                            $('#result').html("An error occurred while searching.");
                        }
                    });
                } else {
                    $('#booksContainer').html("<?php foreach ($allBooks as $book): ?> <div class='text-center bg-gray-100 p-4 rounded-lg shadow-md'> <img alt='The Book of CSS3' class='w-full h-auto rounded-lg' height='300' src='<?php echo $book['cover_image']; ?>' width='200' /> <p name='title' class='mt-4 text-lg font-semibold'><?php echo $book['title']; ?></p> <p name='summary' class='text-gray-600 mt-2'><?php echo $book['summary']; ?></p> <p name='status' class='mt-2 text-gray-500'>Status: <?php echo isset($statusLabels[$book['status']]) ? $statusLabels[$book['status']] : 'Unknown'; ?></p> <button class='mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600'><?php echo $statusValue == 'available' ? 'Emprunter maintenant!' : 'Réserver'; ?></button> </div> <?php endforeach; ?>");
                }
            });
        });
    </script>

<script>
$(document).ready(function() {
    // Code existant pour la recherche...

    // Ajout du gestionnaire d'événements pour le filtre par catégorie
    $('#categoryFilter').on('change', function() {
        const categoryId = $(this).val();
        
        $.ajax({
            url: 'filter_books.php',
            type: 'POST',
            data: { category_id: categoryId },
            success: function(response) {
                $('#booksContainer').html(response);
            },
            error: function() {
                console.error("Une erreur s'est produite lors du filtrage");
            }
        });
    });
});
</script>
</body>
</html>