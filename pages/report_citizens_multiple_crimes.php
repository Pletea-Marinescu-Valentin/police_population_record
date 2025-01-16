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

// Interogare pentru cetățenii cu mai mult de o crimă
$query = "
    SELECT c.First_Name, c.Last_Name, (
        SELECT COUNT(*)
        FROM Crimes cr
        WHERE cr.Citizen_ID = c.Citizen_ID
    ) AS Crime_Count
    FROM Citizens c
    WHERE (
        SELECT COUNT(*)
        FROM Crimes cr
        WHERE cr.Citizen_ID = c.Citizen_ID
    ) > 3;
";

$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Raport: Cetățeni cu mai mult de 3 crime</h2>

<?php if (!empty($results)): ?>
    <table border="1">
        <tr>
            <th>Nume</th>
            <th>Prenume</th>
            <th>Număr de Crime</th>
        </tr>
        <?php foreach ($results as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['Last_Name']); ?></td>
                <td><?= htmlspecialchars($row['First_Name']); ?></td>
                <td><?= htmlspecialchars($row['Crime_Count']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p style="color: red;">Nu există cetățeni cu mai mult de 3 crime.</p>
<?php endif; ?>

<?php
include '../includes/footer.php';
?>
