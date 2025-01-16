<?php
include '../config/database.php';

// Create users with hashed passwords
$users = [
    ['username' => 'admin', 'password' => password_hash('admin123', PASSWORD_BCRYPT), 'role' => 'Admin'],
    ['username' => 'officer1', 'password' => password_hash('officer123', PASSWORD_BCRYPT), 'role' => 'Officer'],
    ['username' => 'user1', 'password' => password_hash('user123', PASSWORD_BCRYPT), 'role' => 'User']
];

foreach ($users as $user) {
    $stmt = $conn->prepare("INSERT INTO Users (Username, Password, Role) VALUES (:username, :password, :role)");
    $stmt->bindParam(':username', $user['username']);
    $stmt->bindParam(':password', $user['password']);
    $stmt->bindParam(':role', $user['role']);
    $stmt->execute();
}

echo "Users were successfully created!";
?>
