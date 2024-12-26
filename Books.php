<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Ajout de jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<?php
require_once 'connection.php';
require_once 'classes/User.php';
require_once 'classes/Book.php';

$book = new Book("", "", 0, "", "", "");
$allBooks = $book->getAllBooks();

session_start();

// Verifier si l'utilisateur est connecte
$isLoggedIn = isset($_SESSION['user_id']);

// Creer une instance de Database et obtenir la connexion PDO
$database = new Database();
$pdo = $database->connect();

if ($isLoggedIn) {
    $user = new User($pdo);
}

// Ajouter le message de succès
$message = '';
if (isset($_SESSION['borrow_message'])) {
    $message = $_SESSION['borrow_message'];
    unset($_SESSION['borrow_message']);
}
?>

<script>
function redirectToLogin() {
    if (confirm('Vous devez être connecté pour effectuer cette action. Voulez-vous vous connecter ?')) {
        window.location.href = 'login.php';
    }
    return false;
}

function openBorrowModal(bookId, bookTitle) {
    document.getElementById('modal-book-id').value = bookId;
    document.getElementById('modal-book-title').textContent = bookTitle;
    document.getElementById('borrow-modal').classList.remove('hidden');
}

function closeBorrowModal() {
    document.getElementById('borrow-modal').classList.add('hidden');
}

function validateDueDate(input) {
    const selectedDate = new Date(input.value);
    const maxDate = new Date();
    maxDate.setDate(maxDate.getDate() + 14);
    
    if (selectedDate > maxDate) {
        alert('La date de retour ne peut pas dépasser 14 jours à partir d\'aujourd\'hui');
        input.value = maxDate.toISOString().split('T')[0];
    }
}

function openReserveModal(bookId, title) {
    // Vérifier d'abord si le livre peut être réservé
    fetch('check_reservation.php?book_id=' + bookId)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modal-book-id').value = bookId;
                document.getElementById('modal-book-title').textContent = title;
                document.getElementById('modal-due-date').textContent = data.due_date;
                
                // Configurer la date minimale de réservation
                const dueDateObj = new Date(data.due_date);
                const minDate = dueDateObj.toISOString().split('T')[0];
                document.getElementById('reservation-date').min = minDate;
                
                document.getElementById('reserve-modal').classList.remove('hidden');
            } else {
                alert(data.message);
            }
        });
}

function closeReserveModal() {
    document.getElementById('reserve-modal').classList.add('hidden');
}
</script>

<body class="bg-white p-8">
    <!-- Message de succes -->
    <?php if ($message): ?>
    <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        <?php echo $message; ?>
    </div>
    <?php endif; ?>

    <h1 class="text-3xl font-bold mb-8">
        ALL BOOKS
    </h1>

    <!-- Modal d'emprunt -->
    <div id="borrow-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Emprunter un livre</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500" id="modal-book-title"></p>
                </div>
                <form action="process_borrow.php" method="POST">
                    <input type="hidden" id="modal-book-id" name="book_id">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Date de retour prévue</label>
                        <input type="date" name="due_date" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               required
                               min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>"
                               max="<?php echo date('Y-m-d', strtotime('+14 days')); ?>"
                               onchange="validateDueDate(this)">
                    </div>
                    <div class="items-center px-4 py-3">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 w-full mb-2">
                            Confirmer l'emprunt
                        </button>
                        <button type="button" onclick="closeBorrowModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 w-full">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de réservation -->
    <div id="reserve-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Réserver un livre</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500" id="modal-book-title"></p>
                    <p class="text-sm text-gray-500">Date de retour prévue : <span id="modal-due-date"></span></p>
                </div>
                <form action="process_book_action.php" method="POST">
                    <input type="hidden" id="modal-book-id" name="book_id">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Date de réservation souhaitée</label>
                        <input type="date" name="reservation_date" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                               required
                               id="reservation-date">
                    </div>
                    <div class="items-center px-4 py-3">
                        <button type="submit" name="action" value="reserve" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 w-full mb-2">
                            Confirmer la réservation
                        </button>
                        <button type="button" onclick="closeReserveModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 w-full">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php foreach ($allBooks as $book): ?>
            <div class="text-center bg-gray-100 p-4 rounded-lg shadow-md">
                <img alt="The Book of CSS3" class="w-full h-auto rounded-lg" height="300" src="<?php echo $book['cover_image']; ?>" width="200" />                
                <p name="title" class="mt-4 text-lg font-semibold">
                    <?php echo $book['title']; ?>
                </p>

                <p name="summary" class="text-gray-600 mt-2">
                    <?php echo $book['summary']; ?>
                </p>

                <p name="status" class="mt-2 text-gray-500">
                    Status: 
                    <?php 
                        $statusLabels = [
                            'available' => 'Disponible',
                            'borrowed' => 'Emprunté',
                            'reserved' => 'Reservé'
                        ];
                        $statusValue = $book['status'];
                        echo isset($statusLabels[$statusValue]) ? $statusLabels[$statusValue] : 'Unknown';
                    ?>
                </p>

                <!-- Bouton pour chaque card -->
                <?php if ($isLoggedIn): ?>
                    <?php if ($statusValue == 'available'): ?>
                        <button type="button" 
                                onclick="openBorrowModal('<?php echo $book['id']; ?>', '<?php echo htmlspecialchars($book['title']); ?>')"
                                class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Emprunter maintenant!
                        </button>
                    <?php else: ?>
                        <form method="POST" action="process_book_action.php">
                            <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                            <button type="submit" 
                                    name="action" 
                                    value="reserve"
                                    class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                Réserver
                            </button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <button type="button" 
                            onclick="return redirectToLogin()"
                            class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        <?php echo $statusValue == 'available' ? 'Emprunter maintenant!' : 'Réserver'; ?>
                    </button>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
