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

// Verify access for Admin and Officer roles
check_access(['Admin', 'Officer']);

// Fetch all officers from the database
$stmt = $conn->query("SELECT * FROM Officers");
$officers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Officers List</h2>
<?php if ($_SESSION['role'] === 'Admin'): ?>
    <a href="officers_add.php">Add Officer</a>
<?php endif; ?>
<a href="officers_search.php">Search Officer</a>

<table border="1">
    <tr>
        <th>Last Name</th>
        <th>First Name</th>
        <th>Rank</th>
        <?php if ($_SESSION['role'] === 'Admin'): ?>
            <th>Actions</th>
        <?php endif; ?>
    </tr>
    <?php foreach ($officers as $officer): ?>
        <tr>
            <td><?= htmlspecialchars($officer['Last_Name']); ?></td>
            <td><?= htmlspecialchars($officer['First_Name']); ?></td>
            <td><?= htmlspecialchars($officer['Rank']); ?></td>
            <?php if ($_SESSION['role'] === 'Admin'): ?>
                <td>
                    <a href="officers_edit.php?id=<?= $officer['Officer_ID']; ?>">Edit</a> |
                    <a href="officers_delete.php?id=<?= $officer['Officer_ID']; ?>" onclick="return confirm('Are you sure you want to delete this officer?');">Delete</a>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
</table>

<?php
include '../includes/footer.php';
?>
