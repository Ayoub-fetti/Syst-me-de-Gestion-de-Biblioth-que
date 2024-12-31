<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Book Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>

<?php
require_once 'connection.php';
require_once 'classes/User.php';
require_once 'classes/Book.php';

$book = new Book("", "", 0, "", "", "");
$categories = $book->getAllCategories();
$books = $book->getAllBooks();

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
    document.getElementById('borrow-book-id').value = bookId;
    document.getElementById('borrow-book-title').textContent = bookTitle;
    document.getElementById('borrowModal').classList.remove('hidden');
}

function closeBorrowModal() {
    document.getElementById('borrowModal').classList.add('hidden');
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

function openReservationModal(bookId, bookTitle, dueDate) {
    document.getElementById('reserve-book-id').value = bookId;
    document.getElementById('reserve-book-title').textContent = bookTitle;
    document.getElementById('current-due-date').textContent = dueDate;
    document.getElementById('reservation_date').min = dueDate;
    document.getElementById('reservationModal').classList.remove('hidden');
}

function closeReservationModal() {
    document.getElementById('reservationModal').classList.add('hidden');
}

// Event listener pour le bouton de fermeture
document.getElementById('closeReservationModal').addEventListener('click', function() {
    closeReservationModal();
});

// Fermer le modal si on clique en dehors
window.addEventListener('click', function(event) {
    let modal = document.getElementById('reservationModal');
    if (event.target === modal) {
        closeReservationModal();
    }
});

// Empêcher la propagation du clic depuis le contenu du modal
document.querySelector('#reservationModal > div').addEventListener('click', function(event) {
    event.stopPropagation();
});
</script>

<body class="bg-white text-gray-800">
    <!-- Header -->
    <header class="bg-black text-white">
        <div class="container mx-auto flex justify-between items-center py-2 px-4">
            <div class="text-2xl font-bold">
                YouBiblio
            </div>
            <div class="flex items-center space-x-2">
                <input class="px-2 py-1 text-black" placeholder="Search category" type="text"/>
                <button class="bg-red-600 px-4 py-1">
                    SEARCH
                </button>
            </div>
        </div>
        <nav class="bg-gray-800">
            <div class="container mx-auto flex justify-between items-center py-2 px-4 text-sm">
                <a class="text-white px-2" href="index.php">ACCEUIL</a>
                <a class="text-white px-2" href="Books.php">LIVRES</a>
                <a class="text-white px-2" href="Books.php">CATEGORIES</a>
                <a class="text-white px-2" href="Books.php">EMPRUNTER</a>
                <a class="text-white px-2" href="Books.php">RESERVER</a>
                <a class="text-white px-2" href="Contact.php">CONTACT</a>
                <a class="text-white px-2" href="register.php">SING UP</a>
                <a class="text-white px-2" href="login.php">LOGIN</a>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto my-4">
        <!-- Messages de succès/erreur -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="container mx-auto px-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?php echo $_SESSION['success_message']; ?></span>
                    <?php unset($_SESSION['success_message']); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Search and Filter Section -->
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row gap-4 mb-6">
                <div class="md:w-1/2">
                    <input type="text" 
                           id="searchInput" 
                           placeholder="Rechercher un livre..." 
                           class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="md:w-1/2">
                    <select id="categoryFilter" 
                            class="w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Toutes les catégories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Books Grid -->
            <div id="booksContainer" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <?php foreach ($books as $book): ?>
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <img src="<?php echo $book['cover_image']; ?>" 
                             alt="<?php echo htmlspecialchars($book['title']); ?>" 
                             class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-2"><?php echo htmlspecialchars($book['title']); ?></h3>
                            <p class="text-gray-600 text-sm mb-4"><?php echo htmlspecialchars($book['summary']); ?></p>
                            
                            <?php if ($isLoggedIn): ?>
                                <?php if ($book['status'] == 'available'): ?>
                                    <button type="button" 
                                            onclick="openBorrowModal('<?php echo $book['id']; ?>', '<?php echo htmlspecialchars($book['title']); ?>')"
                                            class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                        Emprunter maintenant!
                                    </button>
                                <?php else: ?>
                                    <?php
                                    $stmt = $pdo->prepare("SELECT due_date FROM borrowings WHERE book_id = ? AND return_date IS NULL ORDER BY due_date DESC LIMIT 1");
                                    $stmt->execute([$book['id']]);
                                    $currentBorrowing = $stmt->fetch();
                                    $dueDate = $currentBorrowing ? $currentBorrowing['due_date'] : date('Y-m-d');
                                    ?>
                                    <div class="text-sm text-gray-600 mb-2">
                                        Date de retour prévue : <?php echo date('d/m/Y', strtotime($dueDate)); ?>
                                    </div>
                                    <button type="button" 
                                            onclick="openReservationModal(
                                                '<?php echo $book['id']; ?>', 
                                                '<?php echo htmlspecialchars($book['title']); ?>', 
                                                '<?php echo $dueDate; ?>'
                                            )"
                                            class="mt-2 px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">
                                        Réserver
                                    </button>
                                <?php endif; ?>
                            <?php else: ?>
                                <button type="button" 
                                        onclick="return redirectToLogin()"
                                        class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                    Se connecter pour emprunter
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <!-- Modal pour l'emprunt -->
    <div id="borrowModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Emprunter le livre</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500" id="borrow-book-title"></p>
                    <form id="borrowForm" action="process_borrow.php" method="POST" class="mt-4">
                        <input type="hidden" name="book_id" id="borrow-book-id">
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Date de retour prévue</label>
                            <input type="date" name="due_date" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   required>
                        </div>
                        
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            Confirmer l'emprunt
                        </button>
                    </form>
                </div>
                <div class="items-center px-4 py-3">
                    <button onclick="closeBorrowModal()" 
                            class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour la réservation -->
    <div id="reservationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Réserver le livre</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500" id="reserve-book-title"></p>
                    <p class="text-sm text-gray-500 mt-2">
                        Ce livre est actuellement emprunté.
                        <br>Date de retour prévue : <span id="current-due-date"></span>
                    </p>
                    <form id="reservationForm" action="process_book_action.php" method="POST" class="mt-4">
                        <input type="hidden" name="book_id" id="reserve-book-id">
                        <input type="hidden" name="action" value="reserve">
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Date de réservation souhaitée</label>
                            <input type="date" name="reservation_date" id="reservation_date" 
                                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                   required>
                        </div>
                        
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                            Confirmer la réservation
                        </button>
                    </form>
                </div>
                <div class="items-center px-4 py-3">
                    <button onclick="closeReservationModal()" 
                            class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Gestionnaire de recherche
        let searchTimeout;
        $('#searchInput').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchTerm = $(this).val();
                if (searchTerm.length > 0) {
                    $.ajax({
                        url: 'search_books.php',
                        method: 'POST',
                        data: { query: searchTerm },
                        success: function(response) {
                            $('#booksContainer').html(response);
                        }
                    });
                } else {
                    // Si la recherche est vide, réinitialiser le filtre par catégorie
                    $('#categoryFilter').trigger('change');
                }
            }, 300);
        });

        // Gestionnaire de filtre par catégorie
        $('#categoryFilter').on('change', function() {
            const categoryId = $(this).val();
            $.ajax({
                url: 'filter_books.php',
                method: 'POST',
                data: { category_id: categoryId },
                success: function(response) {
                    $('#booksContainer').html(response);
                }
            });
        });
    });
    </script>
</body>
</html>