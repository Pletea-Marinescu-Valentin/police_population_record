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

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Retrieve user details
    $stmt = $conn->prepare("SELECT * FROM Users WHERE User_ID = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $role = $_POST['role'];

        $stmt = $conn->prepare("UPDATE Users SET Role = :role WHERE User_ID = :id");
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':id', $user_id);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>User role updated successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error updating user role.</p>";
        }
    }
}
?>

<h2>Edit User</h2>
<form method="POST" action="">
    <label>Role:</label>
    <select name="role" required>
        <option value="Admin" <?= $user['Role'] === 'Admin' ? 'selected' : ''; ?>>Admin</option>
        <option value="Officer" <?= $user['Role'] === 'Officer' ? 'selected' : ''; ?>>Officer</option>
        <option value="User" <?= $user['Role'] === 'User' ? 'selected' : ''; ?>>User</option>
    </select><br>
    <button type="submit">Save</button>
</form>

<?php
include '../includes/footer.php';
?>
