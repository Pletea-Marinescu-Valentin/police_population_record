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

$query = "SELECT o.First_Name, o.Last_Name, o.Rank,
                 COUNT(DISTINCT cr.Description) AS Total_Types
          FROM Officers o
          JOIN Crimes cr ON o.Officer_ID = cr.Officer_ID
          GROUP BY o.Officer_ID
          HAVING COUNT(DISTINCT cr.Description) = (
              SELECT MAX(Total_Types)
              FROM (
                  SELECT cr.Officer_ID, COUNT(DISTINCT cr.Description) AS Total_Types
                  FROM Crimes cr
                  GROUP BY cr.Officer_ID
              ) AS Subquery
          )";

$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Raport: Ofițeri cu cele mai multe tipuri de crime investigate</h2>

<?php if (!empty($results)): ?>
    <table border="1">
        <tr>
            <th>Prenume</th>
            <th>Nume</th>
            <th>Rang</th>
            <th>Total Tipuri de Crime</th>
        </tr>
        <?php foreach ($results as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['First_Name']); ?></td>
                <td><?= htmlspecialchars($row['Last_Name']); ?></td>
                <td><?= htmlspecialchars($row['Rank']); ?></td>
                <td><?= htmlspecialchars($row['Total_Types']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Nu au fost găsiți ofițeri conform criteriilor specificate.</p>
<?php endif; ?>

<?php
include '../includes/footer.php';
?>
