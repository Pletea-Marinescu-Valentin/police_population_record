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

// Interogare pentru cea mai populară categorie de permis
$query = "
    SELECT Category, COUNT(*) AS Total
    FROM Driving_Licenses
    GROUP BY Category
    HAVING COUNT(*) = (
        SELECT MAX(Total)
        FROM (
            SELECT Category, COUNT(*) AS Total
            FROM Driving_Licenses
            GROUP BY Category
        ) AS Subquery
    );
";

$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Raport: Cea mai populară categorie de permis</h2>

<?php if (!empty($results)): ?>
    <table border="1">
        <tr>
            <th>Categorie Permis</th>
            <th>Număr de Permise</th>
        </tr>
        <?php foreach ($results as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['Category']); ?></td>
                <td><?= htmlspecialchars($row['Total']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p style="color: red;">Nu s-au găsit date pentru categoria de permis.</p>
<?php endif; ?>

<?php
include '../includes/footer.php';
?>
