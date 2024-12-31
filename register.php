<?php
require_once 'connection.php';
require_once 'classes/User.php';

session_start();

// Créer l'instance de Database et obtenir la connexion PDO
$db = new Database();
$pdo = $db->connect();

$errors = [];
$user = new User($pdo);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $passwordC = $_POST['passwordC'];

    // Validation
    if (empty($username)) {
        $errors['name'] = "Le nom d'utilisateur est requis";
    }
    if (empty($email)) {
        $errors['email'] = "L'email est requis";
    }
    if (empty($password)) {
        $errors['password'] = "Le mot de passe est requis";
    }
    if ($password !== $passwordC) {
        $errors['passwordC'] = "Les mots de passe ne correspondent pas";
    }

    if (empty($errors)) {
        // Utilisation de la méthode register de la classe User
        $result = $user->register($username, $email, $password);
        
        if ($result['success']) {
            // Enregistrement réussi
            $_SESSION['success_message'] = $result['message'];
            header("Location: login.php");
            exit();
        } else {
            // Erreur lors de l'enregistrement
            $errors['register'] = $result['message'];
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
        <h2 class="mb-2 text-2xl font-bold text-gray-800">Hello !</h2>
        <p class="mb-6 text-gray-600">Welcome </p>
        <form id="form2" method="POST" action="register.php">
            <div class="mb-4">
                <label class="sr-only" for="username">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-fingerprint text-gray-400"></i>
                    </div>
                    <input type="text" id="username" name="name" class="w-full py-2 pl-10 pr-4 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Username">
                    <div class="error2"></div>
                </div>
            </div>
            <div class="mb-4">
                <label class="sr-only" for="email">Email Address</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-envelope text-gray-400"></i>
                    </div>
                    <input type="email" id="email" name="email" class="w-full py-2 pl-10 pr-4 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Email Address">
                    <div class="error2"></div>
                </div>
            </div>
            <div class="mb-6">
                <label class="sr-only" for="password">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" id="password" name="password" class="w-full py-2 pl-10 pr-4 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Password">
                    <div class="error2"></div>
                </div>
            </div>
            <div class="mb-6">
                <label class="sr-only" for="passwordC">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-lock text-gray-400"></i>
                    </div>
                    <input type="password" id="passwordC" name="passwordC" class="w-full py-2 pl-10 pr-4 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Confirm password">
                    <div class="error2"></div>
                </div>
            </div>
            <button type="submit" class="w-full py-2 text-white bg-blue-500 rounded-lg hover:bg-blue-300 focus:outline-none focus:ring-2 focus:ring-blue-500">Sign Up</button>
        </form>
        <div class="mt-4 text-center">
            <a href="login.php" class="text-sm text-gray-600 hover:underline">You have an account ?</a>
        </div>
    </div>
</body>
</html>