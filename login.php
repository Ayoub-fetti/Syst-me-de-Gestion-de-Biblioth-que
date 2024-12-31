<?php
define('BASE_URL', 'http://localhost/votre-projet'); // Ajustez selon votre configuration

require_once 'connection.php';
require_once 'classes/User.php';
session_start();

$db = new Database();
$pdo = $db->connect();

$errors = [];
$user = new User($pdo);



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validation
    if (empty($email)) {
        $errors['email'] = "L'email est requis";
    }
    if (empty($password)) {
        $errors['password'] = "Le mot de passe est requis";
    }

    if (empty($errors)) {
        // Utilisation de la méthode login de la classe User
        if ($user->login($email, $password)) {
            // Stockage des informations de l'utilisateur en session
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_name'] = $user->getName();
            $_SESSION['user_role'] = $user->getRole();

            // Redirection selon le rôle
            switch($user->getRole()) {
                case 'admin':
                    header("Location: admin/admin_dashboard.php");
                    break;
                case 'visitor':
                case 'authenticated':
                    header("Location: index.php");
                    break;
                default:
                    header("Location: index.php");
            }
            exit();
        } else {
            $errors['login'] = "Email ou mot de passe incorrect";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/index.php">
    <style>
        body {
            background-image: url('https://images.pexels.com/photos/1370298/pexels-photo-1370298.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
        }
        
        .container {
            background-color: rgba(255, 255, 255, 0.9); /* Fond blanc semi-transparent */
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="container max-w-md mx-auto mt-10">
        <?php if (!empty($errors)): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <?php foreach($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                <?php 
                echo htmlspecialchars($_SESSION['success_message']); 
                unset($_SESSION['success_message']); // Effacer le message après l'avoir affiché
                ?>
            </div>
        <?php endif; ?>
        <h2 class="mb-2 text-2xl font-bold text-gray-800">Hello Again!</h2>
        <p class="mb-6 text-gray-600">Welcome back</p>
        <form id="form" method="POST" action="login.php">
            <div class="mb-4">
                <label class="sr-only" for="email">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input type="email" id="email" name="email" class="w-full py-2 pl-10 pr-4 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Email Address">
                    <div class="error"></div>
                </div>
            </div>
            <div class="mb-6">
                <label class="sr-only" for="password">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" id="password" name="password" class="w-full py-2 pl-10 pr-4 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"  placeholder="Password">
                    <div class="error"></div>
                </div>
            </div>
            <button type="submit" id="submit" class="w-full py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-300 focus:outline-none focus:ring-2 focus:ring-amber-500">
                Login</button>
        </form>
        <div class="mt-4 text-center">
            <a href="register.php" class="text-sm text-gray-600 hover:underline">Create account ?</a>
        </div>
 
    </div>
</body>
</html>