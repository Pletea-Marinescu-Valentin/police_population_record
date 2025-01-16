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

// Only Admin and Officer can access
check_access(['Admin', 'Officer']);

// Retrieve all crimes from the database
$stmt = $conn->query("SELECT c.Crime_ID, c.Description, c.Crime_Date, 
                      CONCAT(ci.Last_Name, ' ', ci.First_Name) AS Citizen_Name, 
                      CONCAT(o.Last_Name, ' ', o.First_Name) AS Officer_Name
                      FROM Crimes c
                      LEFT JOIN Citizens ci ON c.Citizen_ID = ci.Citizen_ID
                      LEFT JOIN Officers o ON c.Officer_ID = o.Officer_ID");
$crimes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Crimes List</h2>
<?php if ($_SESSION['role'] === 'Admin'): ?>
    <a href="crimes_add.php">Add New Crime</a>
<?php endif; ?>
<a href="crimes_search.php">Search Crime</a>
<table border="1">
    <tr>
        <th>Description</th>
        <th>Date</th>
        <th>Citizen</th>
        <th>Officer</th>
        <?php if ($_SESSION['role'] === 'Admin'): ?>
            <th>Actions</th>
        <?php endif; ?>
    </tr>
    <?php foreach ($crimes as $crime): ?>
        <tr>
            <td><?= htmlspecialchars($crime['Description']); ?></td>
            <td><?= htmlspecialchars($crime['Crime_Date']); ?></td>
            <td><?= htmlspecialchars($crime['Citizen_Name']); ?></td>
            <td><?= htmlspecialchars($crime['Officer_Name']); ?></td>
            <?php if ($_SESSION['role'] === 'Admin'): ?>
                <td>
                    <a href="crimes_edit.php?id=<?= $crime['Crime_ID']; ?>">Edit</a> |
                    <a href="crimes_delete.php?id=<?= $crime['Crime_ID']; ?>" onclick="return confirm('Are you sure you want to delete this crime?');">Delete</a>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
</table>

<?php
include '../includes/footer.php';
?>
