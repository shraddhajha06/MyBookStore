<?php
// Get the JSON payload from the frontend
$data = json_decode(file_get_contents('php://input'), true);

// Process the cart (store in database, process payment, etc.)
if (isset($data) && is_array($data)) {
  // Example: Process and store the cart items (this could be saved to a database)
  // For simplicity, we just simulate a successful checkout

  $totalAmount = 0;
  foreach ($data as $item) {
    $totalAmount += $item['price'];  // Sum the item prices
  }

  // Simulate successful checkout response
  echo json_encode(['status' => 'success', 'total' => $totalAmount]);
} else {
  echo json_encode(['status' => 'error', 'message' => 'Invalid cart data']);
}
?>
