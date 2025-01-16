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

// Fetch details about the current user
$query = "SELECT Citizens.*, 
                 CONCAT(Addresses.City, ', ', Addresses.Street, ' ', Addresses.Number) AS Address,
                 Identity_Documents.Series, Identity_Documents.Number
          FROM Citizens
          LEFT JOIN Addresses ON Citizens.Address_ID = Addresses.Address_ID
          LEFT JOIN Identity_Documents ON Citizens.Citizen_ID = Identity_Documents.Citizen_ID
          WHERE Citizens.Citizen_ID = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$citizen = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch driving license details
$query_license = "SELECT * FROM Driving_Licenses WHERE Citizen_ID = :id";
$stmt_license = $conn->prepare($query_license);
$stmt_license->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt_license->execute();
$license = $stmt_license->fetch(PDO::FETCH_ASSOC);

// Fetch crimes associated with the user
$query_crimes = "SELECT Crimes.Description, Crimes.Crime_Date 
                 FROM Crimes 
                 WHERE Citizen_ID = :id";
$stmt_crimes = $conn->prepare($query_crimes);
$stmt_crimes->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt_crimes->execute();
$crimes = $stmt_crimes->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>My Profile</h2>
<?php if ($citizen): ?>
    <p><strong>Last Name:</strong> <?= htmlspecialchars($citizen['Last_Name']); ?></p>
    <p><strong>First Name:</strong> <?= htmlspecialchars($citizen['First_Name']); ?></p>
    <p><strong>Date of Birth:</strong> <?= htmlspecialchars($citizen['Birth_Date']); ?></p>
    <p><strong>Address:</strong> <?= htmlspecialchars($citizen['Address']); ?></p>
    <p><strong>ID Series:</strong> <?= htmlspecialchars($citizen['Series'] ?? 'N/A'); ?></p>
    <p><strong>ID Number:</strong> <?= htmlspecialchars($citizen['Number'] ?? 'N/A'); ?></p>

    <?php if ($license): ?>
        <h3>Driving License</h3>
        <p><strong>Category:</strong> <?= htmlspecialchars($license['Category']); ?></p>
        <p><strong>Issue Date:</strong> <?= htmlspecialchars($license['Issue_Date']); ?></p>
        <p><strong>Expiry Date:</strong> <?= htmlspecialchars($license['Expiry_Date']); ?></p>
    <?php else: ?>
        <h3>Driving License</h3>
        <p>You do not have an associated driving license.</p>
    <?php endif; ?>

    <h3>Crimes</h3>
    <?php if (!empty($crimes)): ?>
        <table border="1">
            <tr>
                <th>Description</th>
                <th>Date of Crime</th>
            </tr>
            <?php foreach ($crimes as $crime): ?>
                <tr>
                    <td><?= htmlspecialchars($crime['Description']); ?></td>
                    <td><?= htmlspecialchars($crime['Crime_Date']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>You have no associated crimes.</p>
    <?php endif; ?>
<?php else: ?>
    <p>User details not found.</p>
<?php endif; ?>

<?php
include '../includes/footer.php';
?>
