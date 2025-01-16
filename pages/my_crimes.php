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

// Only normal users can access this page
check_access(['User']);

// Fetch crimes for the logged-in user
$query = "SELECT Crimes.* FROM Crimes 
          INNER JOIN Citizens ON Crimes.Citizen_ID = Citizens.Citizen_ID
          WHERE Citizens.Citizen_ID = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $_SESSION['user_id']);
$stmt->execute();
$crimes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>My Crimes</h2>
<?php if (!empty($crimes)): ?>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Description</th>
            <th>Date of Crime</th>
        </tr>
        <?php foreach ($crimes as $crime): ?>
            <tr>
                <td><?= $crime['Crime_ID']; ?></td>
                <td><?= htmlspecialchars($crime['Description']); ?></td>
                <td><?= htmlspecialchars($crime['Crime_Date']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>You have no associated crimes.</p>
<?php endif; ?>

<?php
include '../includes/footer.php';
?>
