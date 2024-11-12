<?php
// Database connection
$dsn = 'mysql:host=localhost;dbname=user_auth';
$username = 'root'; // Change as needed
$password = '';     // Change as needed

try {
    $db = new PDO($dsn, $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Register user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email is already registered
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->rowCount() > 0) {
        die("Email already registered.");
    }

    // Hash the password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into database
    $stmt = $db->prepare("INSERT INTO users (email, password_hash) VALUES (:email, :password_hash)");
    if ($stmt->execute(['email' => $email, 'password_hash' => $passwordHash])) {
        echo "Registration successful! You can now <a href='login.html'>login</a>.";
    } else {
        echo "Registration failed. Please try again.";
    }
}
?>
