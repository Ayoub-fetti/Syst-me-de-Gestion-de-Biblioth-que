<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des livres</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="m-0 p-0">

<header class="bg-gray-800 text-white">
   <div class="container mx-auto flex justify-between items-center py-2 px-4">
    <div class="text-2xl font-bold">
     YouBiblio
    </div>

    </div>

   </div>
   <nav class="bg-blue-600">
    <div class="container mx-auto flex justify-between items-center py-2 px-4 text-sm">
     <a class="text-white font-semibold hover:text-gray-300 px-2" href="index.php">
      ACCEUIL
     </a>
     <a class="text-white font-semibold hover:text-gray-300 px-2" href="Books.php">
      LIVRES
     </a>
     <a class="text-white font-semibold hover:text-gray-300 px-2" href="Books.php">
      CATEGORIES
     </a>
     <a class="text-white font-semibold hover:text-gray-300 px-2" href="Books.php">
      EMPRUNTER
     </a>
     <a class="text-white font-semibold hover:text-gray-300 px-2" href="Books.php">
      RESERVER
     </a>
     <a class="text-white font-semibold hover:text-gray-300 px-2" href="#">
      CONTACT
     </a>
     <a class="text-white font-semibold hover:text-gray-300 px-2" href="register.php">
      SING UP
     </a>
     <a class="text-white font-semibold hover:text-gray-300 px-2" href="login.php">
    LOGIN 
     </a>
    </div>
   </nav>
  </header>

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


<div class="bg-white p-8">
    <!-- Messages de succès/erreur -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><?php echo $_SESSION['success_message']; ?></span>
            <?php unset($_SESSION['success_message']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><?php echo $_SESSION['error_message']; ?></span>
            <?php unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>

    <!-- Message de succes -->
    <?php if ($message): ?>
    <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        <?php echo $message; ?>
    </div>
    <?php endif; ?>

    <h1 class="text-3xl font-bold mb-8">
        ALL BOOKS
    </h1>

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

    <!-- Barre de recherche -->
    <div class="mb-6">
        <input type="text" id="searchInput" placeholder="Rechercher un livre..." 
               class="w-full p-2 border rounded-lg">
    </div>

    <!-- Filtre par catégorie -->
    <div class="mb-6">
        <select id="categoryFilter" class="w-full p-2 border rounded-lg">
            <option value="">Toutes les catégories</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Container pour les livres -->
    <div id="booksContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($books as $book): ?>
            <div class="book-card text-center bg-gray-100 p-4 rounded-lg shadow-md w-[450px] h-[480px] mx-auto overflow-y-auto flex flex-col"
                 data-category="<?php echo $book['category_id']; ?>">
                <div class="flex-grow">
                    <img alt="<?php echo htmlspecialchars($book['title']); ?>" 
                         class="w-[250px] h-[250px] rounded-lg object-cover mx-auto" 
                         src="<?php echo $book['cover_image']; ?>" />                
                    <p name="title" class="mt-4 text-lg font-semibold">
                        <?php echo $book['title']; ?>
                    </p>
                    <p class="text-gray-600 mt-2">
                        Par : <?php echo htmlspecialchars($book['author']); ?>
                    </p>
                    <p class="text-gray-700 mb-4 text-justify hidden book-description">
                        <?php echo htmlspecialchars($book['summary']); ?>
                    </p>
                    <button type="button" 
                            class="show-more-btn px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600">
                        Plus d'infos
                    </button>
                </div>
                <div class="mt-auto">
                    <?php if ($isLoggedIn): ?>
                        <?php if ($book['status'] == 'available'): ?>
                            <button type="button" 
                                    onclick="openBorrowModal('<?php echo $book['id']; ?>', '<?php echo htmlspecialchars($book['title']); ?>')"
                                    class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
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
                                class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Se connecter pour emprunter
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
    $(document).ready(function() {
        function updateBooks(searchTerm = '', categoryId = '') {
            const books = $('#booksContainer .book-card');
            
            books.each(function() {
                const title = $(this).find('[name="title"]').text().toLowerCase();
                const shouldShowBySearch = !searchTerm || title.includes(searchTerm.toLowerCase());
                const shouldShowByCategory = !categoryId || $(this).data('category') == categoryId;
                
                $(this).toggle(shouldShowBySearch && shouldShowByCategory);
            });
        }

        // Search handler
        let searchTimeout;
        $('#searchInput').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                updateBooks($(this).val(), $('#categoryFilter').val());
            }, 300);
        });

        // Category filter handler
        $('#categoryFilter').on('change', function() {
            updateBooks($('#searchInput').val(), $(this).val());
        });

        // Gestionnaire pour le bouton "Plus d'infos"
        $('.show-more-btn').on('click', function() {
            const description = $(this).prev('.book-description');
            description.toggleClass('hidden');
            $(this).text(description.hasClass('hidden') ? 'Plus d\'infos' : 'Moins d\'infos');
        });
    });

    // final
    </script>
</div>
</body>
</html>