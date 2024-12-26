<?php
require_once 'classes/Book.php';
require_once 'connection.php';

$book = new Book("", "", 0, "", "", "");

if (isset($_POST['query'])) {
    $searchTerm = $_POST['query'];
    $results = $book->searchBooks($searchTerm);

    foreach ($results as $book) {
        ?>
        <div class="text-center bg-gray-100 p-4 rounded-lg shadow-md">
            <img alt="The Book of CSS3" class="w-full h-auto rounded-lg" height="300" src="<?php echo $book['cover_image']; ?>" width="200" />
            <p name="title" class="mt-4 text-lg font-semibold"><?php echo htmlspecialchars($book['title']); ?></p>
            <p name="summary" class="text-gray-600 mt-2"><?php echo htmlspecialchars($book['summary']); ?></p>
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
        <?php
    }
}
?> 