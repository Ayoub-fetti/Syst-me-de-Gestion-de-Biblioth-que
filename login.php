<?php
require_once 'config.php';
session_start();

$errors = [];

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

    // Verification dans la base de données
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $isValid = false;
            if ($user['role'] === 'admin' && $password === $user['password']) {
                // Cas spécial pour l'admin avec mot de passe en texte brut
                $isValid = true;
            } else {
                // Pour les autres utilisateurs, vérification normale avec password_verify
                $isValid = password_verify($password, $user['password']);
            }

            if ($isValid) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];

                // Redirection en fonction du role
                switch($user['role']) {
                    case 'admin':
                        header("Location: admin_dashbord.php");
                        break;
                    case 'visitor':
                        header("Location: #");
                        break;
                    case 'authenticated':
                        header("Location: user_dashbord.php");
                        break;
                    default:
                        header("Location: index.php");
                }
                exit();
            }
        }
        $errors['login'] = "Email ou mot de passe incorrect";
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