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

$query = "SELECT Officers.Officer_ID, Officers.First_Name, Officers.Last_Name, Officers.Rank,
                 COUNT(Crimes.Crime_ID) AS Total_Cases,
                 CONCAT(Citizens.First_Name, ' ', Citizens.Last_Name) AS Citizen_Name,
                 CONCAT(Addresses.City, ', ', Addresses.Street, ' ', Addresses.Number) AS Address
          FROM Officers
          LEFT JOIN Crimes ON Officers.Officer_ID = Crimes.Officer_ID
          LEFT JOIN Citizens ON Crimes.Citizen_ID = Citizens.Citizen_ID
          LEFT JOIN Addresses ON Citizens.Address_ID = Addresses.Address_ID
          GROUP BY Officers.Officer_ID";

$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Raport: Ofițeri și Cazuri Investigate</h2>
<table border="1">
    <tr>
        <th>Nume</th>
        <th>Prenume</th>
        <th>Funcție</th>
        <th>Total Cazuri</th>
        <th>Cetățean Asociat</th>
        <th>Adresă</th>
    </tr>
    <?php foreach ($results as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['First_Name']); ?></td>
            <td><?= htmlspecialchars($row['Last_Name']); ?></td>
            <td><?= htmlspecialchars($row['Rank']); ?></td>
            <td><?= htmlspecialchars($row['Total_Cases']); ?></td>
            <td><?= htmlspecialchars($row['Citizen_Name']); ?></td>
            <td><?= htmlspecialchars($row['Address']); ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include '../includes/footer.php'; ?>
