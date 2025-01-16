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

// Query to find citizens with the longest-held driving license
$query = "SELECT CONCAT(c.First_Name, ' ', c.Last_Name) AS Citizen_Name, 
                 TIMESTAMPDIFF(YEAR, dl.Issue_Date, CURDATE()) AS License_Years
          FROM Citizens c
          JOIN Driving_Licenses dl ON c.Citizen_ID = dl.Citizen_ID
          WHERE TIMESTAMPDIFF(YEAR, dl.Issue_Date, CURDATE()) = (
              SELECT MAX(TIMESTAMPDIFF(YEAR, Issue_Date, CURDATE()))
              FROM Driving_Licenses
          )";

$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Report: Citizens with the Longest-Held Driving License</h2>
<?php if (!empty($results)): ?>
    <table border="1">
        <tr>
            <th>Citizen</th>
            <th>Years with License</th>
        </tr>
        <?php foreach ($results as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['Citizen_Name']); ?></td>
                <td><?= htmlspecialchars($row['License_Years']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No data available.</p>
<?php endif; ?>

<?php
include '../includes/footer.php';
?>
