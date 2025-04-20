<?php
require_once 'config.php';

$sampleBooks = [
    [
        'title' => 'The Great Gatsby',
        'author' => 'F. Scott Fitzgerald',
        'price' => 12.99,
        'image' => 'Images/great-gatsby.jpg',
        'description' => 'A story of the fabulously wealthy Jay Gatsby and his love for the beautiful Daisy Buchanan.'
    ],
    [
        'title' => 'To Kill a Mockingbird',
        'author' => 'Harper Lee',
        'price' => 14.99,
        'image' => 'Images/mockingbird.jpg',
        'description' => 'The story of racial injustice and the loss of innocence in the American South.'
    ],
    [
        'title' => '1984',
        'author' => 'George Orwell',
        'price' => 11.99,
        'image' => 'Images/1984.jpg',
        'description' => 'A dystopian social science fiction novel and cautionary tale.'
    ],
    [
        'title' => 'Pride and Prejudice',
        'author' => 'Jane Austen',
        'price' => 10.99,
        'image' => 'Images/pride-prejudice.jpg',
        'description' => 'A romantic novel of manners that follows the emotional development of Elizabeth Bennet.'
    ],
    [
        'title' => 'The Catcher in the Rye',
        'author' => 'J.D. Salinger',
        'price' => 13.99,
        'image' => 'Images/catcher-rye.jpg',
        'description' => 'The story of teenage alienation and loss of innocence in the 1950s.'
    ],
    [
        'title' => 'The Hobbit',
        'author' => 'J.R.R. Tolkien',
        'price' => 15.99,
        'image' => 'Images/hobbit.jpg',
        'description' => 'The adventure of Bilbo Baggins, a hobbit who embarks on a quest to help a group of dwarves.'
    ],
    [
        'title' => 'Brave New World',
        'author' => 'Aldous Huxley',
        'price' => 12.99,
        'image' => 'Images/brave-new-world.jpg',
        'description' => 'A dystopian novel that anticipates developments in reproductive technology and sleep-learning.'
    ],
    [
        'title' => 'The Lord of the Rings',
        'author' => 'J.R.R. Tolkien',
        'price' => 29.99,
        'image' => 'Images/lotr.jpg',
        'description' => 'An epic high-fantasy novel that follows the quest to destroy a powerful ring.'
    ],
    [
        'title' => 'Crime and Punishment',
        'author' => 'Fyodor Dostoevsky',
        'price' => 11.99,
        'image' => 'Images/crime-punishment.jpg',
        'description' => 'A psychological novel that explores the moral dilemmas of Raskolnikov.'
    ],
    [
        'title' => 'The Alchemist',
        'author' => 'Paulo Coelho',
        'price' => 9.99,
        'image' => 'Images/alchemist.jpg',
        'description' => 'A philosophical book about following your dreams and listening to your heart.'
    ]
];

try {
    // Clear existing books
    $pdo->exec("TRUNCATE TABLE books");
    
    // Insert sample books
    $stmt = $pdo->prepare("INSERT INTO books (title, author, price, image, description) VALUES (?, ?, ?, ?, ?)");
    
    foreach ($sampleBooks as $book) {
        $stmt->execute([
            $book['title'],
            $book['author'],
            $book['price'],
            $book['image'],
            $book['description']
        ]);
    }
    
    echo "Sample books added successfully!";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 