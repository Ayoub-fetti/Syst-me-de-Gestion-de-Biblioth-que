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
                
                // Stocker les informations nécessaires en session
                $_SESSION['user_id'] = $this->id;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_password'] = $password; // Note: ceci n'est pas une bonne pratique de sécurité
                
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

public function borrowBook($bookId, $dueDate = null) {
    try {
        // Vérifier si le livre est disponible
        $stmt = $this->pdo->prepare("SELECT status FROM books WHERE id = ?");
        $stmt->execute([$bookId]);
        $book = $stmt->fetch();

        if (!$book || $book['status'] !== 'available') {
            return ['success' => false, 'message' => "Ce livre n'est pas disponible."];
        }

        // check si l'utilisateur a deja emprunte ce livre
        $stmt = $this->pdo->prepare("SELECT * FROM borrowings WHERE user_id = ? AND book_id = ? AND return_date IS NULL");
        $stmt->execute([$this->id, $bookId]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => "Vous avez déjà emprunté ce livre."];
        }

        // Commencer une transaction
        $this->pdo->beginTransaction();

        // Creer l'emprunt dans la table borrowings
        $stmt = $this->pdo->prepare("INSERT INTO borrowings (user_id, book_id, borrow_date, due_date) VALUES (?, ?, CURDATE(), ?)");
        $stmt->execute([
            $this->id, 
            $bookId, 
            $dueDate ?? date('Y-m-d', strtotime('+14 days')) // Utilise la date fournie ou par défaut +14 jours
        ]);

        // update  le statut du livre
        $stmt = $this->pdo->prepare("UPDATE books SET status = 'borrowed' WHERE id = ?");
        $stmt->execute([$bookId]);

        // Valider la transaction
        $this->pdo->commit();

        return ['success' => true, 'message' => "Livre emprunté avec succès."];
    } catch(PDOException $e) {
        // En cas d'erreur, annuler la transaction
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }
        return ['success' => false, 'message' => "Erreur lors de l'emprunt: " . $e->getMessage()];
    }
}

public function reserveBook($bookId) {
    try {
        // check si le livre est emprunte et obtenir la date de retour prevue
        $stmt = $this->pdo->prepare("
            SELECT b.status, br.due_date 
            FROM books b 
            LEFT JOIN borrowings br ON b.id = br.book_id 
            WHERE b.id = ? AND br.return_date IS NULL
            ORDER BY br.borrow_date DESC 
            LIMIT 1
        ");
        $stmt->execute([$bookId]);
        $book = $stmt->fetch();

        if (!$book || $book['status'] !== 'borrowed') {
            return ['success' => false, 'message' => "Ce livre ne peut pas être réservé actuellement."];
        }

        // Verifier si l'utilisateur a deja reserve ce livre
        $stmt = $this->pdo->prepare("
            SELECT * FROM borrowings 
            WHERE user_id = ? AND book_id = ? 
            AND return_date IS NULL 
            AND reservation_date IS NOT NULL
        ");
        $stmt->execute([$this->id, $bookId]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => "Vous avez déjà réservé ce livre."];
        }

        return [
            'success' => true, 
            'due_date' => $book['due_date'],
            'message' => "Livre disponible pour réservation."
        ];
    } catch(PDOException $e) {
        return ['success' => false, 'message' => "Erreur lors de la vérification: " . $e->getMessage()];
    }
}

public function confirmReservation($bookId, $reservationDate) {
    try {
        // check si le livre existe
        $stmt = $this->pdo->prepare("SELECT status FROM books WHERE id = ?");
        $stmt->execute([$bookId]);
        $book = $stmt->fetch();

        if (!$book) {
            return ['success' => false, 'message' => "Livre non trouvé."];
        }

        // Verifier si l'utilisateur a deja reserve ce livre
        $stmt = $this->pdo->prepare("
            SELECT * FROM borrowings 
            WHERE user_id = ? AND book_id = ? 
            AND return_date IS NULL
        ");
        $stmt->execute([$this->id, $bookId]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => "Vous avez déjà emprunté ou réservé ce livre."];
        }

        // Commencer une transaction
        $this->pdo->beginTransaction();

        try {
            // Creer une nouvelle reservation sans le champ status
            $stmt = $this->pdo->prepare("
                INSERT INTO borrowings (user_id, book_id, borrow_date, due_date) 
                VALUES (?, ?, ?, DATE_ADD(?, INTERVAL 14 DAY))
            ");
            $stmt->execute([$this->id, $bookId, $reservationDate, $reservationDate]);

            // Mettre a jour le statut du livre si ce n'est pas deja fait
            if ($book['status'] == 'available') {
                $stmt = $this->pdo->prepare("UPDATE books SET status = 'reserved' WHERE id = ?");
                $stmt->execute([$bookId]);
            }

            $this->pdo->commit();
            return ['success' => true, 'message' => "Livre réservé avec succès pour le " . $reservationDate];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    } catch(PDOException $e) {
        return ['success' => false, 'message' => "Erreur lors de la réservation: " . $e->getMessage()];
    }
}

public function setId($id) {
    $this->id = $id;
}


// fonction pour recuperer les utilisateurs les plus actives 

public function getMostActiveUsers($limit = 5) {
    try {
        $query = "
            SELECT 
                u.id,
                u.name,
                u.email,
                COUNT(br.id) as borrow_count
            FROM users u
            LEFT JOIN borrowings br ON u.id = br.user_id
            GROUP BY u.id, u.name, u.email
            HAVING borrow_count > 0
            ORDER BY borrow_count DESC
            LIMIT " .intval($limit);

        $stmt = $this->pdo->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);


    } catch(PDOException $e) {
        error_log("Erreur dans getMostActiveUsers: " . $e->getMessage());
        return [];
    }
}

// fonction pour recuperer les statistiques des utilisateur 
public function getUserStatistics() {
    try {
        $stats = [];
        
        // Total des utilisateurs
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
        $stats['total_users'] = $stmt->fetchColumn();
        
        // Utilisateurs actifs (avec au moins un emprunt)
        $stmt = $this->pdo->query("
            SELECT COUNT(DISTINCT user_id) 
            FROM borrowings 
            WHERE return_date IS NULL
        ");
        $stats['active_users'] = $stmt->fetchColumn();
        
        // Emprunts actifs
        $stmt = $this->pdo->query("
            SELECT COUNT(*) 
            FROM borrowings 
            WHERE return_date IS NULL
        ");
        $stats['active_borrowings'] = $stmt->fetchColumn();
        
        return $stats;
    } catch(PDOException $e) {
        return [];
    }
}

}

?>