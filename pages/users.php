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

// Retrieve users from the database
$stmt = $conn->query("SELECT * FROM Users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>User List</h2>
<a href="users_add.php">Add User</a>

<table border="1">
    <tr>
        <th>Username</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['Username']); ?></td>
            <td><?= htmlspecialchars($user['Role']); ?></td>
            <td>
                <a href="users_edit.php?id=<?= $user['User_ID']; ?>">Edit</a> |
                <a href="users_delete.php?id=<?= $user['User_ID']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php
include '../includes/footer.php';
?>
