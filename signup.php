<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    
    // Perform data validation and database insertion here
    // Make sure to use prepared statements to protect against SQL injection
    
    // Redirect to a thank-you page or display a success message
    header("Location: thank_you.html");
    exit;
}
?>
