<?php
require_once 'config.php';

header('Content-Type: application/json');

// Get all books or a single book
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        if (isset($_GET['id'])) {
            // Get single book
            $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $book = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($book) {
                echo json_encode($book);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Book not found']);
            }
        } else {
            // Get all books
            $stmt = $pdo->query("SELECT * FROM books");
            $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($books);
        }
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch books']);
    }
}

// Add a new book
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['title']) || !isset($data['author']) || !isset($data['price']) || !isset($data['image'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO books (title, author, price, image, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['title'],
            $data['author'],
            $data['price'],
            $data['image'],
            $data['description'] ?? ''
        ]);
        
        $bookId = $pdo->lastInsertId();
        echo json_encode(['id' => $bookId, 'message' => 'Book added successfully']);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to add book']);
    }
}

// Update a book
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Book ID is required']);
        exit;
    }

    try {
        $updates = [];
        $params = [];
        
        if (isset($data['title'])) {
            $updates[] = "title = ?";
            $params[] = $data['title'];
        }
        if (isset($data['author'])) {
            $updates[] = "author = ?";
            $params[] = $data['author'];
        }
        if (isset($data['price'])) {
            $updates[] = "price = ?";
            $params[] = $data['price'];
        }
        if (isset($data['image'])) {
            $updates[] = "image = ?";
            $params[] = $data['image'];
        }
        if (isset($data['description'])) {
            $updates[] = "description = ?";
            $params[] = $data['description'];
        }
        
        if (empty($updates)) {
            http_response_code(400);
            echo json_encode(['error' => 'No fields to update']);
            exit;
        }
        
        $params[] = $data['id'];
        $sql = "UPDATE books SET " . implode(', ', $updates) . " WHERE id = ?";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        echo json_encode(['message' => 'Book updated successfully']);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update book']);
    }
}

// Delete a book
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Book ID is required']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
        $stmt->execute([$data['id']]);
        
        echo json_encode(['message' => 'Book deleted successfully']);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete book']);
    }
}
?> 