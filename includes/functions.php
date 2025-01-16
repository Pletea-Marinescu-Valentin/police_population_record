<?php

// Function to sanitize inputs to prevent XSS (Cross-Site Scripting)
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Function to hash passwords (used during account creation)
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Function to verify passwords
function verify_password($password, $hashed_password) {
    return password_verify($password, $hashed_password);
}

// Function to check if a user is authenticated
function check_authentication() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

// Function to verify if the user has access based on roles
function check_access($roles) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles)) {
        echo "<p style='color: red;'>You do not have permission to access this page.</p>";
        exit;
    }
}
?>
