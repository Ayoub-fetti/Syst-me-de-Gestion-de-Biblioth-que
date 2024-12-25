<?php
require_once 'connection.php';
require_once 'classes/User.php';
session_start();

$db = new Database();
$pdo = $db->connect();

// Création de l'instance User et récupération des utilisateurs
$user = new User($pdo);
$users = $user->getAllUsers();

// nombre d'utilisateurs
$userQuery = $pdo->query("SELECT COUNT(*) as total_users FROM users");
$userCount = $userQuery->fetch(PDO::FETCH_ASSOC)['total_users'];

// nombre total des livres
$totalBooksQuery = $pdo->query("SELECT COUNT(*) as total_books FROM books");
$totalBooksCount = $totalBooksQuery->fetch(PDO::FETCH_ASSOC)['total_books'];

// nombre de categories
$categoryQuery = $pdo->query("SELECT COUNT(*) as total_categories FROM categories");
$categoryCount = $categoryQuery->fetch(PDO::FETCH_ASSOC)['total_categories'];

// nombre de livres reserves
$reservedQuery = $pdo->query("SELECT COUNT(*) as total_reserved FROM books WHERE status = 'reserved'");
$reservedCount = $reservedQuery->fetch(PDO::FETCH_ASSOC)['total_reserved'];

// nombre de livres empruntes
$borrowedQuery = $pdo->query("SELECT COUNT(*) as total_borrowed FROM books WHERE status = 'borrowed'");
$borrowedCount = $borrowedQuery->fetch(PDO::FETCH_ASSOC)['total_borrowed'];

// Traitement du changement de rôle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userId']) && isset($_POST['newRole'])) {
    $result = $user->changeUserRole($_POST['userId'], $_POST['newRole']);
    if ($result['success']) {
        $_SESSION['success_message'] = "Rôle mis à jour avec succès";
    } else {
        $_SESSION['error_message'] = "Erreur lors de la mise à jour du rôle";
    }
    header('Location: admin_dashboard.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>
   Dashboard
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="<?php echo BASE_URL; ?>/public/css/style.css" rel="stylesheet">
 </head>
 <body class="bg-gray-100 font-sans antialiased">
  <div class="flex">
   <!-- Sidebar -->
   <div class="w-64 bg-blue-900 text-white min-h-screen">
    <div class="p-4 flex items-center">
     <img alt="Logo" class="w-10 h-10 rounded-full" src="<?php echo BASE_URL; ?>/public/images/logo.jpg"/>
     <span class="ml-3 text-xl font-semibold">
      Admin Dashbord
     </span>
    </div>
    <nav class="mt-5">
     <a class="flex items-center p-3 bg-blue-800 rounded-lg" href="<?php echo BASE_URL; ?>/views/dashboard/admin.php">
      <i class="fas fa-tachometer-alt">
      </i>
      <span class="ml-3">
       Dashboard
      </span>
     </a>
     <div class="mt-5">
  
      <a class="flex items-center p-3 hover:bg-blue-800 rounded-lg" href="<?php echo BASE_URL; ?>/views/components/index.php">
       <i class="fas fa-cube">
       </i>
       <span class="ml-3">
        Components
       </span>
      </a>
     </div>
     <div class="mt-5">
      <a class="flex items-center p-3 hover:bg-blue-800 rounded-lg" href="<?php echo BASE_URL; ?>/views/forms/index.php">
       <i class="fas fa-edit">
       </i>
       <span class="ml-3">
        Form elements
       </span>
      </a>
      <a class="flex items-center p-3 hover:bg-blue-800 rounded-lg" href="<?php echo BASE_URL; ?>/views/tables/index.php">
       <i class="fas fa-table">
       </i>
       <span class="ml-3">
        Table
       </span>
      </a>
     </div>
     <div class="mt-5">
      <a class="flex items-center p-3 hover:bg-blue-800 rounded-lg" href="<?php echo BASE_URL; ?>/views/charts/index.php">
       <i class="fas fa-chart-bar">
       </i>
       <span class="ml-3">
        Chart
       </span>
      </a>
      <a class="flex items-center p-3 hover:bg-blue-800 rounded-lg" href="<?php echo BASE_URL; ?>/views/maps/index.php">
       <i class="fas fa-map">
       </i>
       <span class="ml-3">
        Maps
       </span>
      </a>
     </div>
     <div class="mt-5">
      <a class="flex items-center p-3 hover:bg-blue-800 rounded-lg" href="<?php echo BASE_URL; ?>/views/auth/login.php">
       <i class="fas fa-user">
       </i>
       <span class="ml-3">
        Authentication
       </span>
      </a>
      <a class="flex items-center p-3 hover:bg-blue-800 rounded-lg" href="<?php echo BASE_URL; ?>/views/sample/index.php">
       <i class="fas fa-file">
       </i>
       <span class="ml-3">
        Sample page
       </span>
      </a>
     </div>
    </nav>
   </div>
   <!-- Main Content -->
   <div class="flex-1 p-6">
    <div class="flex justify-between items-center mb-6">
     <h1 class="text-2xl font-semibold">
      Dashboard
     </h1>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
     <div class="bg-white p-4 rounded-lg shadow">
      <div class="flex items-center justify-between">
       <div>
        <p class="text-gray-600">
         Total des utilisateurs
        </p>
        <p class="text-2xl font-semibold text-green-500">
         <?php echo $userCount; ?>
        </p>
       </div>
       <i class="fas fa-user text-green-500"></i>
       </i>
      </div>
      <div class="mt-4">
       <div class="h-2 bg-green-500 rounded-full" style="width: 100%;">
       </div>
      </div>
     </div>
     <div class="bg-white p-4 rounded-lg shadow">
      <div class="flex items-center justify-between">
       <div>
        <p class="text-gray-600">Total des livres</p>
        <p class="text-2xl font-semibold text-green-500"><?php echo $totalBooksCount; ?></p>
       </div>
       <i class="fas fa-books text-green-500"></i>
      </div>
      <div class="mt-4">
       <div class="h-2 bg-green-500 rounded-full" style="width: 100%;"></div>
      </div>
     </div>
     <div class="bg-white p-4 rounded-lg shadow">
      <div class="flex items-center justify-between">
       <div>
        <p class="text-gray-600">
         Categories
        </p>
        <p class="text-2xl font-semibold text-green-500">
         <?php echo $categoryCount; ?>
        </p>
       </div>
      
       <i class="fas fa-bookmark text-green-500"></i>
       </i>
      </div>
      <div class="mt-4">
       <div class="h-2 bg-green-500 rounded-full" style="width: 100%;">
       </div>
      </div>
     </div>
     <div class="bg-white p-4 rounded-lg shadow">
      <div class="flex items-center justify-between">
       <div>
        <p class="text-gray-600">
         Livres réservé
        </p>
        <p class="text-2xl font-semibold text-green-500">
         <?php echo $reservedCount; ?>
        </p>
       </div>
     
       <i class="fas fa-book text-green-500"></i>
       </i>
      </div>
      <div class="mt-4">
       <div class="h-2 bg-green-500 rounded-full" style="width: 100%;">
       </div>
      </div>
     </div>
     <div class="bg-white p-4 rounded-lg shadow">
      <div class="flex items-center justify-between">
       <div>
        <p class="text-gray-600">
         Livres emprientés
        </p>
        <p class="text-2xl font-semibold text-green-500">
         <?php echo $borrowedCount; ?>
        </p>
       </div>
       
       <i class="fas fa-book-open text-green-500"></i>
       </i>
      </div>
      <div class="mt-4">
       <div class="h-2 bg-green-500 rounded-full" style="width: 100%;">
       </div>
      </div>
     </div>
  


    </div>
    <div class="bg-white p-6 rounded-lg shadow mb-6">
     <h2 class="text-xl font-semibold mb-4">Utilisateurs</h2>
     <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Nom
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Role
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date d'inscription
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($users as $userItem): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                <?php echo htmlspecialchars($userItem['name']); ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">
                                <?php echo htmlspecialchars($userItem['email']); ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php echo $userItem['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'; ?>">
                                <?php echo ucfirst(htmlspecialchars($userItem['role'])); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo date('d/m/Y H:i', strtotime($userItem['created_at'])); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-2">
                                <div class="flex space-x-2">
                                    <form method="GET" action="edit_user.php" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $userItem['id']; ?>">
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                                            Modifier
                                        </button>
                                    </form>

                                    <?php if ($userItem['role'] !== 'admin'): ?>
                                        <form method="POST" action="delete_user.php" style="display: inline;">
                                            <input type="hidden" name="id" value="<?php echo $userItem['id']; ?>">
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded" 
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                                Supprimer
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($userItem['id'] !== $_SESSION['user_id']): ?>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir changer le rôle de cet utilisateur ?');">
                                        <input type="hidden" name="userId" value="<?php echo $userItem['id']; ?>">
                                        <select name="newRole" onchange="this.form.submit()" class="w-22 bg-gray-100 border border-gray-300 rounded px-1 py-1">
                                            <option value="authenticated" <?php echo $userItem['role'] === 'authenticated' ? 'selected' : ''; ?>>Authenticated</option>
                                            <option value="admin" <?php echo $userItem['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                        </select>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
     <div class="bg-white p-6 rounded-lg shadow">
      <h2 class="text-xl font-semibold mb-4">
       Upcoming Event
      </h2>
      <div class="flex items-center justify-between">
       <div>
        <p class="text-3xl font-semibold">
         45
        </p>
        <p class="text-gray-500">
         Competitors
        </p>
       </div>
       <div class="text-purple-500 text-4xl">
        <i class="fas fa-hand-peace">
        </i>
       </div>
      </div>
      <div class="mt-4">
       <p class="text-gray-500">
        You can participate in event
       </p>
       <div class="h-2 bg-purple-500 rounded-full mt-2" style="width: 34%;">
       </div>
      </div>
     </div>
     <div class="bg-white p-6 rounded-lg shadow">
      <div class="flex items-center justify-between mb-4">
       <div>
        <p class="text-3xl font-semibold">
         235
        </p>
        <p class="text-gray-500">
         Total Ideas
        </p>
       </div>
       <div class="text-green-500 text-4xl">
        <i class="fas fa-lightbulb">
        </i>
       </div>
      </div>
      <div class="flex items-center justify-between">
       <div>
        <p class="text-3xl font-semibold">
         26
        </p>
        <p class="text-gray-500">
         Total Locations
        </p>
       </div>
       <div class="text-blue-500 text-4xl">
        <i class="fas fa-map-marker-alt">
        </i>
       </div>
      </div>
     </div>
    </div>
   </div>
  </div>
  <!-- Notification -->
  <div id="notification" class="fixed top-4 right-4 px-4 py-2 rounded-lg shadow-lg transform transition-all duration-300 opacity-0 translate-y-[-100%]">
  </div>

  <?php
  // Ajouter au début de la page, après la balise body, pour afficher les messages
  if (isset($_SESSION['success_message'])): ?>
      <div class="fixed top-4 right-4 px-4 py-2 bg-green-500 text-white rounded-lg shadow-lg">
          <?php 
          echo $_SESSION['success_message'];
          unset($_SESSION['success_message']);
          ?>
      </div>
  <?php endif; ?>

  <?php if (isset($_SESSION['error_message'])): ?>
      <div class="fixed top-4 right-4 px-4 py-2 bg-red-500 text-white rounded-lg shadow-lg">
          <?php 
          echo $_SESSION['error_message'];
          unset($_SESSION['error_message']);
          ?>
      </div>
  <?php endif; ?>
 </body>
</html>
