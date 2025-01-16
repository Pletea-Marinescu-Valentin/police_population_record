<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Handle logout
if (isset($_GET['logout'])) {
    // Destroy the session
    session_unset();
    session_destroy();
    // Redirect to the homepage
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Police Population Record</title>
    <link rel="stylesheet" href="../assets/style.css">  <!-- Link to CSS file -->
</head>
<body>

<header>
    <h1>Police Population Record</h1>

    <?php if (isset($_SESSION['user_id'])): ?>
        <nav>
            <ul>
                <?php if ($_SESSION['role'] === 'Admin'): ?>
                    <li><a href="citizens.php">Citizens</a></li>
                    <li><a href="officers.php">Officers</a></li>
                    <li><a href="crimes.php">Crimes</a></li>
                    <li><a href="identify_documents.php">Identity Documents</a></li>
                    <li><a href="driving_licenses.php">Driving Licenses</a></li>
                    <li><a href="users.php">Users</a></li>
                    <li><a href="reports.php">Reports</a></li>
                <?php elseif ($_SESSION['role'] === 'Officer'): ?>
                    <li><a href="citizens.php">Citizens</a></li>
                    <li><a href="officers.php">Officers</a></li>
                    <li><a href="crimes.php">Crimes</a></li>
                    <li><a href="identify_documents.php">Identity Documents</a></li>
                    <li><a href="driving_licenses.php">Driving Licenses</a></li>
                    <li><a href="reports.php">Reports</a></li>
                <?php elseif ($_SESSION['role'] === 'User'): ?>
                    <li><a href="my_profile.php">My Profile</a></li>
                <?php endif; ?>
                <li><a href="?logout=true">Logout</a></li>
            </ul>
        </nav>
    <?php endif; ?>
</header>

<main>
