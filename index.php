<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'config/database.php';
// Redirect to the dashboard if the user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: pages/dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police Population Record</title>
    <link rel="stylesheet" href="assets/index.css"> <!-- Include dedicated CSS -->
</head>
<body>

<header>
    <h1>Police Population Record</h1>
</header>

<main>
    <div class="welcome-message">
        <h2>Welcome to the Police Population Record</h2>
        <p>Please log in to access the application.</p>
        <a href="pages/login.php"><button>Login</button></a>
        <p>Don’t have an account? <a href="pages/signup.php">Create one</a></p>
    </div>
</main>
</body>
</html>
