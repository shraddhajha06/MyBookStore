<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'bookstore';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$table = isset($_GET['table']) ? $_GET['table'] : '';

switch($method) {
    case 'GET':
        if ($table) {
            try {
                $stmt = $pdo->query("SELECT * FROM $table");
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($data);
            } catch(PDOException $e) {
                echo json_encode(['error' => 'Error fetching data: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['error' => 'Table name not specified']);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if ($table && $data) {
            try {
                $columns = implode(', ', array_keys($data));
                $values = ':' . implode(', :', array_keys($data));
                $sql = "INSERT INTO $table ($columns) VALUES ($values)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($data);
                echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            } catch(PDOException $e) {
                echo json_encode(['error' => 'Error inserting data: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['error' => 'Invalid data or table name']);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if ($table && $data && isset($data['id'])) {
            try {
                $set = [];
                foreach($data as $key => $value) {
                    if ($key !== 'id') {
                        $set[] = "$key = :$key";
                    }
                }
                $sql = "UPDATE $table SET " . implode(', ', $set) . " WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute($data);
                echo json_encode(['success' => true]);
            } catch(PDOException $e) {
                echo json_encode(['error' => 'Error updating data: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['error' => 'Invalid data or table name']);
        }
        break;

    case 'DELETE':
        if ($table && isset($_GET['id'])) {
            try {
                $sql = "DELETE FROM $table WHERE id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['id' => $_GET['id']]);
                echo json_encode(['success' => true]);
            } catch(PDOException $e) {
                echo json_encode(['error' => 'Error deleting data: ' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['error' => 'Invalid table name or ID']);
        }
        break;

    default:
        echo json_encode(['error' => 'Method not allowed']);
        break;
}
?> 