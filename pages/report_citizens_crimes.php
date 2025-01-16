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

$query = "SELECT Citizens.Citizen_ID, Citizens.Last_Name, Citizens.First_Name,
                 CONCAT(Addresses.City, ', ', Addresses.Street, ' ', Addresses.Number) AS Address,
                 Crimes.Description AS Crime_Description, Crimes.Crime_Date,
                 CONCAT(Officers.First_Name, ' ', Officers.Last_Name) AS Officer_Name
          FROM Citizens
          LEFT JOIN Addresses ON Citizens.Address_ID = Addresses.Address_ID
          LEFT JOIN Crimes ON Citizens.Citizen_ID = Crimes.Citizen_ID
          LEFT JOIN Officers ON Crimes.Officer_ID = Officers.Officer_ID
          WHERE Crimes.Crime_Date IS NOT NULL";

$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Report: Citizens and Their Crimes</h2>
<table border="1">
    <tr>
        <th>Last Name</th>
        <th>First Name</th>
        <th>Address</th>
        <th>Crime Description</th>
        <th>Crime Date</th>
        <th>Officer</th>
    </tr>
    <?php foreach ($results as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['Last_Name']); ?></td>
            <td><?= htmlspecialchars($row['First_Name']); ?></td>
            <td><?= htmlspecialchars($row['Address']); ?></td>
            <td><?= htmlspecialchars($row['Crime_Description']); ?></td>
            <td><?= htmlspecialchars($row['Crime_Date']); ?></td>
            <td><?= htmlspecialchars($row['Officer_Name']); ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include '../includes/footer.php'; ?>
