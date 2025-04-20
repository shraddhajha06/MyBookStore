<?php
session_start();

// Check if the user is already logged in, redirect to a welcome page if logged in.
if (isset($_SESSION["username"])) {
    header("Location: welcome.php");
    exit;
}

// Check if the login form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Replace with your database connection logic
    $dbHost = "localhost";
    $dbUser = "your_db_username";
    $dbPass = "your_db_password";
    $dbName = "your_db_name";

    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate user credentials (replace with your authentication logic)
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Authentication successful, set a session variable
        $_SESSION["username"] = $username;

        // Redirect to a welcome page or dashboard
        header("Location: welcome.php");
        exit;
    } else {
        // Authentication failed, display an error message
        echo "Invalid username or password";
    }

    $stmt->close();
    $conn->close();
}
?>
