<?php 

require_once dirname(__DIR__) . '/connection.php';

class user {
    private $pdo;
    private $id;
    private $name;
    private $email;
    private $role;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // getters 

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getEmail() { return $this->email; }
    public function getRole() { return $this->role; }

    // login method 
    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            $isValid = false;
            if ($user['role'] === 'admin' && $password === $user['password']) {
                $isValid = true;
            } else {
                $isValid = password_verify($password, $user['password']);
            }

            if ($isValid) {
                $this->id = $user['id'];
                $this->name = $user['name'];
                $this->email = $user['email'];
                $this->role = $user['role'];
                return true;
            }
        }
        return false;
    }
 // Register method
 public function register($name, $email, $password) {
    try {
        // VErifier si l'email existe dEjA
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'message' => "Cet email est déjà utilisé"];
        }

        // Creer le nouvel utilisateur
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'authenticated')");
        $stmt->execute([$name, $email, $hashedPassword]);

        return ['success' => true, 'message' => "Inscription réussie!"];
    } catch(PDOException $e) {
        return ['success' => false, 'message' => "Erreur lors de l'inscription: " . $e->getMessage()];
    }
}

// Get user by ID
public function getUserById($id) {
    $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Update user profile
public function updateProfile($id, $name, $email) {
    try {
        $stmt = $this->pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->execute([$name, $email, $id]);
        return ['success' => true, 'message' => "Profil mis à jour avec succès"];
    } catch(PDOException $e) {
        return ['success' => false, 'message' => "Erreur lors de la mise à jour: " . $e->getMessage()];
   

}
}

// Recuperer tous les utilisateurs
public function getAllUsers() {
    $stmt = $this->pdo->prepare("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Changer le role d'un utilisateur
public function changeUserRole($userId, $newRole) {
    try {
        $stmt = $this->pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->execute([$newRole, $userId]);
        return ['success' => true, 'message' => "Rôle mis à jour avec succès"];
    } catch(PDOException $e) {
        return ['success' => false, 'message' => "Erreur lors de la mise à jour du rôle: " . $e->getMessage()];
    }
}

// Supprimer un utilisateur
public function deleteUser($userId) {
    try {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return ['success' => true, 'message' => "Utilisateur supprimé avec succès"];
    } catch(PDOException $e) {
        return ['success' => false, 'message' => "Erreur lors de la suppression: " . $e->getMessage()];
    }
}

}

?>