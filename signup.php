<?php
require_once 'config.php';
session_start();

$errors = [];
$success = false;

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->query("SELECT 1");
} catch(PDOException $e) {
    die("Erreur de connexion: " . $e->getMessage());
}

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

    // Vérification si l'email existe déjà
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            $errors['email'] = "Cet email est déjà utilisé";
        }
    }

    // Inscription
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hashedPassword]);
            $_SESSION['message'] = "Inscription réussie!";
            header("Location: index.php");
            exit();
        } catch(PDOException $e) {
            $errors['db'] = "Erreur lors de l'inscription: " . $e->getMessage();
            error_log($e->getMessage());
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
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-sm p-6 bg-white rounded-lg shadow-md">
        <?php if (!empty($errors)): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                <?php foreach($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <h2 class="mb-2 text-2xl font-bold text-gray-800">Hello !</h2>
        <p class="mb-6 text-gray-600">Welcome </p>
        <form id="form2" method="POST" action="<?php echo BASE_URL; ?>/signup.php">
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
            <a href="<?php echo BASE_URL; ?>/index.php" class="text-sm text-gray-600 hover:underline">You have an account ?</a>
        </div>
    </div>
</body>
</html>