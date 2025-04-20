<?php
require_once 'config.php';
session_start();

header('Content-Type: application/json');

// Get user's cart
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'User not logged in']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("
            SELECT c.*, b.title, b.author, b.price, b.image 
            FROM cart c 
            JOIN books b ON c.book_id = b.id 
            WHERE c.user_id = ?
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($cartItems);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch cart items']);
    }
}

// Add item to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'User not logged in']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['book_id']) || !isset($data['quantity'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    try {
        // Check if item already exists in cart
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND book_id = ?");
        $stmt->execute([$_SESSION['user_id'], $data['book_id']]);
        $existingItem = $stmt->fetch();

        if ($existingItem) {
            // Update quantity if item exists
            $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + ? WHERE id = ?");
            $stmt->execute([$data['quantity'], $existingItem['id']]);
        } else {
            // Add new item to cart
            $stmt = $pdo->prepare("INSERT INTO cart (user_id, book_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $data['book_id'], $data['quantity']]);
        }
        
        echo json_encode(['message' => 'Item added to cart successfully']);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to add item to cart']);
    }
}

// Update cart item quantity
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'User not logged in']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['book_id']) || !isset($data['quantity'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND book_id = ?");
        $stmt->execute([$data['quantity'], $_SESSION['user_id'], $data['book_id']]);
        
        echo json_encode(['message' => 'Cart updated successfully']);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update cart']);
    }
}

// Remove item from cart
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'User not logged in']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['book_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Book ID is required']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND book_id = ?");
        $stmt->execute([$_SESSION['user_id'], $data['book_id']]);
        
        echo json_encode(['message' => 'Item removed from cart successfully']);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to remove item from cart']);
    }
}
?> 