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

// Retrieve all driving licenses
$stmt = $conn->query("SELECT dl.License_ID, dl.Category, dl.Issue_Date, dl.Expiry_Date,
                             CONCAT(c.Last_Name, ' ', c.First_Name) AS Citizen_Name
                      FROM Driving_Licenses dl
                      LEFT JOIN Citizens c ON dl.Citizen_ID = c.Citizen_ID");
$licenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Driving Licenses</h2>
<?php if ($_SESSION['role'] === 'Admin'): ?>
    <a href="driving_licenses_add.php">Add License</a>
<?php endif; ?>
<a href="driving_licenses_search.php">Search License</a>
<table border="1">
    <tr>
        <th>Category</th>
        <th>Issue Date</th>
        <th>Expiry Date</th>
        <th>Citizen</th>
        <?php if ($_SESSION['role'] === 'Admin'): ?>
            <th>Actions</th>
        <?php endif; ?>
    </tr>
    <?php foreach ($licenses as $license): ?>
        <tr>
            <td><?= htmlspecialchars($license['Category']); ?></td>
            <td><?= htmlspecialchars($license['Issue_Date']); ?></td>
            <td><?= htmlspecialchars($license['Expiry_Date']); ?></td>
            <td><?= htmlspecialchars($license['Citizen_Name']); ?></td>
            <?php if ($_SESSION['role'] === 'Admin'): ?>
                <td>
                    <a href="driving_licenses_edit.php?id=<?= $license['License_ID']; ?>">Edit</a> |
                    <a href="driving_licenses_delete.php?id=<?= $license['License_ID']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
</table>

<?php
include '../includes/footer.php';
?>
