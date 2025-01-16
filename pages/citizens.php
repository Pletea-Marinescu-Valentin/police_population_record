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

// Only Admin and Officer can access this page
check_access(['Admin', 'Officer']);

// Retrieve all citizens from the database
$stmt = $conn->query("SELECT * FROM Citizens");
$citizens = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Citizens List</h2>
<?php if ($_SESSION['role'] === 'Admin'): ?>
    <a href="citizens_add.php">Add Citizen</a>
<?php endif; ?>
<a href="citizens_search.php">Search Citizen</a>
<table border="1">
    <tr>
        <th>Last Name</th>
        <th>First Name</th>
        <th>Date of Birth</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($citizens as $citizen): ?>
        <tr>
            <td><?= htmlspecialchars($citizen['Last_Name']); ?></td>
            <td><?= htmlspecialchars($citizen['First_Name']); ?></td>
            <td><?= htmlspecialchars($citizen['Birth_Date']); ?></td>
            <td>
                <?php if ($_SESSION['role'] === 'Admin'): ?>
                    <a href="citizens_edit.php?id=<?= $citizen['Citizen_ID']; ?>">Edit</a> |
                    <a href="citizens_delete.php?id=<?= $citizen['Citizen_ID']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                <?php elseif ($_SESSION['role'] === 'Officer'): ?>
                    <a href="citizens_view.php?id=<?= $citizen['Citizen_ID']; ?>">View</a>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php
include '../includes/footer.php';
?>
