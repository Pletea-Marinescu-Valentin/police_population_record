<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include '../config/database.php';
include '../includes/header.php';
include '../includes/functions.php';

// Only Admins are allowed access
check_access(['Admin']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO Users (Username, Password, Role) VALUES (:username, :password, :role)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>User added successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error adding user.</p>";
    }
}
?>

<h2>Add User</h2>
<form method="POST" action="">
    <label>Username:</label>
    <input type="text" name="username" required><br>
    <label>Password:</label>
    <input type="password" name="password" required><br>
    <label>Role:</label>
    <select name="role" required>
        <option value="Admin">Admin</option>
        <option value="Officer">Officer</option>
        <option value="User">User</option>
    </select><br>
    <button type="submit">Add</button>
</form>

<?php
include '../includes/footer.php';
?>
