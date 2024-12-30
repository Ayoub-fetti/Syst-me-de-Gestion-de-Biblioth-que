<?php
define('BASE_URL', 'http://localhost/votre-projet');
require_once '../connection.php';
require_once '../classes/Book.php';
require_once '../classes/User.php';
require_once 'check_admin.php';

// initialiser la connexion
$db = new Database();
$pdo = $db->connect();

// Creer les instances
$book = new Book("", "", 0, "", "", "");
$user = new User($pdo);

// Recuperer les statistiques
$bookStats = $book->getBookStatistics();
$userStats = $user->getUserStatistics();
$mostBorrowedBooks = $book->getMostBorrowedBooks(5);
$mostActiveUsers = $user->getMostActiveUsers(5);


?>

<!DOCTYPE html>
<html>
<head>
    <title>Rapport et Statistiques</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex">
                <!-- Sidebar -->
            <div class="w-64 bg-blue-900 text-white min-h-screen">
                <div class="p-4 flex items-center">
                    <span class="ml-3 text-xl font-semibold">
                        <span class="text-blue-200">Admin</span> Dashboard
                    </span>
                </div>
                <nav class="mt-5">
                    <div class="mt-5">
                        <a class="flex items-center p-3 hover:bg-blue-800 rounded-lg" href="admin_dashboard.php">
                            <i class="fas fa-home"></i>
                            <span class="ml-3">Dashboard</span>
                        </a>
                    </div>
                    <div class="mt-5">
                        <a class="flex items-center p-3 hover:bg-blue-800 rounded-lg" href="admin_books.php">
                            <i class="fas fa-book"></i>
                            <span class="ml-3">Gestion des Livres</span>
                        </a>
                    </div>
                    <div class="mt-5">
                        <a class="flex items-center p-3 hover:bg-blue-800 rounded-lg" href="admin_categories.php">
                            <i class="fas fa-filter"></i>
                            <span class="ml-3">Gestion des categories</span>
                        </a>
                    </div>
                    <div class="mt-5">
                        <a class="flex items-center p-3 bg-blue-800 rounded-lg" href="admin_rapport.php">
                            <i class="fas fa-file-pdf"></i>
                            <span class="ml-3">statistiques et rapports</span>
                        </a>
                    </div>
                    <div class="mt-5">
                        <a href="../logout.php" class="flex items-center p-3 hover:bg-blue-800 rounded-lg text-red-500 hover:text-red-400">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="ml-3">Déconnexion</span>
                        </a>
                    </div>
                </nav>
            </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <div class="container mx-auto">
                <h1 class="text-3xl font-bold mb-8">Rapport et Statistiques</h1>

                <!-- Statistiques generales -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                                <i class="fas fa-book text-blue-500 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500">Total des livres</p>
                                <p class="text-2xl font-semibold"><?php echo $bookStats['total_books']; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                                <i class="fas fa-users text-green-500 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500">Total des utilisateurs</p>
                                <p class="text-2xl font-semibold"><?php echo $userStats['total_users']; ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-500 bg-opacity-10">
                                <i class="fas fa-chart-bar text-yellow-500 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-500">Total des emprunts</p>
                                <p class="text-2xl font-semibold"><?php echo $bookStats['total_borrowings']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Livres les plus empruntes -->
                <div class="bg-white rounded-lg shadow mb-8 overflow-hidden">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-semibold">Livres les plus empruntés</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Auteur</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre d'emprunts</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($mostBorrowedBooks as $book): ?>
                                <tr>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($book['title']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($book['author']); ?></td>
                                    <td class="px-6 py-4"><?php echo $book['borrow_count']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Utilisateurs les plus actifs -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-semibold">Utilisateurs les plus actifs</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre d'emprunts</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($mostActiveUsers as $user): ?>
                                <tr>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td class="px-6 py-4"><?php echo $user['borrow_count']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- le bouton de téléchargement PDF -->
                <div class="mt-8 mb-8 flex justify-center">
                    <a href="generate_pdf_report.php" 
                       class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-lg flex items-center">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Télécharger le rapport PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>