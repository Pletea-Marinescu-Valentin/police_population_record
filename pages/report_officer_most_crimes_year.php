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

$results = [];
$year = isset($_POST['year']) ? $_POST['year'] : null;

if ($year) {
    $query = "SELECT o.First_Name, o.Last_Name, COUNT(cr.Crime_ID) AS Total_Crimes
              FROM Officers o
              JOIN Crimes cr ON o.Officer_ID = cr.Officer_ID AND YEAR(cr.Crime_Date) = :year
              GROUP BY o.Officer_ID
              HAVING COUNT(cr.Crime_ID) = (
                  SELECT MAX(Total_Crimes)
                  FROM (
                      SELECT COUNT(cr.Crime_ID) AS Total_Crimes
                      FROM Officers o
                      JOIN Crimes cr ON o.Officer_ID = cr.Officer_ID AND YEAR(cr.Crime_Date) = :year
                      GROUP BY o.Officer_ID
                  ) AS Subquery
              )";

    $stmt = $conn->prepare($query);
    $stmt->bindValue(':year', $year, PDO::PARAM_INT);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<h2>Raport: Ofițerul Cu Cele Mai Multe Crime Investigate Într-un An</h2>

<form method="POST" action="">
    <label>Selectați anul:</label>
    <input type="number" name="year" min="2000" max="<?= date('Y'); ?>" value="<?= htmlspecialchars($year); ?>" required>
    <button type="submit">Generați Raport</button>
</form>

<?php if (!empty($results)): ?>
    <table border="1">
        <tr>
            <th>Prenume</th>
            <th>Nume</th>
            <th>Total Crime</th>
        </tr>
        <?php foreach ($results as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['First_Name']); ?></td>
                <td><?= htmlspecialchars($row['Last_Name']); ?></td>
                <td><?= htmlspecialchars($row['Total_Crimes']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php elseif ($year): ?>
    <p>Nu există date pentru anul selectat.</p>
<?php endif; ?>

<?php
include '../includes/footer.php';
?>
