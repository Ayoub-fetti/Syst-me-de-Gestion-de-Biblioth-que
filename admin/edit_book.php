<?php
define('BASE_URL', 'http://localhost/votre-projet');
require_once '../connection.php';
require_once '../classes/Book.php';
require_once '../classes/User.php';
require_once 'check_admin.php';

$message = '';
$book = new Book("", "", 0, "", "", "");

// Récupérer le livre à modifier
if (isset($_GET['id'])) {
    $bookData = $book->getBookById($_GET['id']);
    if ($bookData) {
        $book = new Book(
            $bookData['title'],
            $bookData['author'],
            $bookData['category_id'],
            $bookData['cover_image'],
            $bookData['summary'],
            $bookData['status']
        );
        $book->setId($_GET['id']);
    }
}

// Récupérer les catégories
$database = new Database;
$conn = $database->connect();
$categories = [];

if ($conn) {
    try {
        $stmt = $conn->query("SELECT * FROM categories ORDER BY name");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }
}

if (isset($_POST['submit'])) {
    // Mettre à jour les données du livre
    $book = new Book(
        $_POST['title'],
        $_POST['author'],
        $_POST['category_id'],
        $bookData['cover_image'],
        $_POST['summary'],
        $_POST['status']
    );
    $book->setId($_GET['id']);

    // Gestion de la nouvelle image si fournie
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['cover_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            // Supprimer l'ancienne image si elle existe
            if (!empty($bookData['cover_image']) && file_exists('../' . $bookData['cover_image'])) {
                unlink('../' . $bookData['cover_image']);
            }
            
            // Modifier le chemin de sauvegarde pour inclure le dossier parent
            $cover_image = 'covers/' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['cover_image']['tmp_name'], '../' . $cover_image);
            $book->setCoverImage($cover_image);
        }
    }

    // Sauvegarder les modifications
    if ($book->updateBook()) {
        $message = "Le livre a été modifié avec succès!";
        // Rediriger vers la page admin avec le message
        header('Location: admin_books.php?message=' . urlencode($message));
        exit();
    } else {
        $message = "Erreur lors de la modification du livre.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier un Livre</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white p-8">
    <div class="container mx-auto">
        <h2 class="text-3xl font-bold mb-8">Modifier un Livre</h2>
        
        <?php if ($message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="max-w-2xl">
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Titre</label>
                <input type="text" name="title" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       value="<?php echo htmlspecialchars($bookData['title'] ?? ''); ?>" required>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Auteur</label>
                <input type="text" name="author" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       value="<?php echo htmlspecialchars($bookData['author'] ?? ''); ?>" required>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Catégorie</label>
                <select name="category_id" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                        required>
                    <option value="">Sélectionner une catégorie</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" 
                                <?php echo ($bookData['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Image de couverture</label>
                <?php if (!empty($bookData['cover_image'])): ?>
                    <img src="<?php echo '../' . htmlspecialchars($bookData['cover_image']); ?>" 
                         class="w-[250px] h-[250px] rounded-lg object-cover mb-4">
                <?php endif; ?>
                <input type="file" name="cover_image" 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Résumé</label>
                <textarea name="summary" 
                          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-justify" 
                          rows="5"><?php echo htmlspecialchars($bookData['summary'] ?? ''); ?></textarea>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                <select name="status" 
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                        required>
                    <option value="available" <?php echo ($bookData['status'] == 'available') ? 'selected' : ''; ?>>Disponible</option>
                    <option value="borrowed" <?php echo ($bookData['status'] == 'borrowed') ? 'selected' : ''; ?>>Emprunté</option>
                </select>
            </div>

            <div class="flex gap-4">
                <button type="submit" name="submit" 
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Modifier
                </button>
                <a href="admin_books.php" 
                   class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600">
                    Retour
                </a>
            </div>
        </form>
    </div>
</body>
</html>