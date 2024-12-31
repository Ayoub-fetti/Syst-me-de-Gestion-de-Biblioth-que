-- Base de données : Bibliothèque
CREATE DATABASE bibliotheque;
USE bibliotheque;


-- Table des utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'authenticated', 'visitor') DEFAULT 'visitor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des catégories de livres
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Table des livres
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    cover_image VARCHAR(255), 
    summary TEXT,
    status ENUM('available', 'borrowed', 'reserved') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Table des emprunts
CREATE TABLE borrowings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    borrow_date DATE NOT NULL,
    due_date DATE NOT NULL,
    return_date DATE DEFAULT NULL,
    notification_sent TINYINT(1) DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE
);

-- Afficher les tables
SHOW TABLES;

USE bibliotheque;
-- Insertion dans la table des utilisateurs
INSERT INTO users (name, email, password, role) 
VALUES 
('Admin User', 'admin@example.com', 'adminpassword', 'admin'),
('Authenticated User', 'authenticated@example.com', 'userpassword', 'authenticated'),
('Visitor User', 'visitor@example.com', 'visitorpassword', 'visitor');

-- Insertion dans la table des catégories de livres
INSERT INTO categories (name) 
VALUES 
('Science Fiction'),
('Fantasy'),
('Mystery'),
('Non-Fiction');

-- Insertion dans la table des livres
USE bibliotheque;
INSERT INTO books (title, author, category_id, cover_image, summary, status) 
VALUES 
('Dune', 'Frank Herbert', , 'dune_cover.jpg', 'A science fiction novel set in the distant future.', 'available'),
('The Hobbit', 'J.R.R. Tolkien', , 'hobbit_cover.jpg', 'A fantasy novel about a hobbit named Bilbo Baggins.', 'borrowed'),
('The Hound of the Baskervilles', 'Arthur Conan Doyle', , 'hound_baskervilles_cover.jpg', 'A detective story featuring Sherlock Holmes and Dr. Watson.', 'available'),
('Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', , 'sapiens_cover.jpg', 'A non-fiction book exploring the history of humankind.', 'reserved');

-- Insertion dans la table des emprunts
INSERT INTO borrowings (user_id, book_id, borrow_date, due_date) 
VALUES 
(2, 2, '2024-12-01', '2024-12-15'),
(3, 3, '2024-12-05', '2024-12-20');



select * FROM books;


SELECT * FROM categories;

SHOW TABLES;


SELECT * FROM books;

USE bibliotheque;
SHOW TABLES;
SELECT * FROM books;

SELECT summary FROM books;

USE bibliotheque;
SHOW TABLES;
SELECT * FROM users;
SELECT * FROM books;
