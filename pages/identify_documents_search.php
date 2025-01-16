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
$search_series = '';
$search_number = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_series = trim($_POST['series']);
    $search_number = trim($_POST['number']);

    $query = "SELECT c.Citizen_ID, c.Last_Name, c.First_Name, c.SSN, c.Birth_Date,
                     id.Series, id.Number,
                     COUNT(cr.Crime_ID) AS Total_Crimes
              FROM Identity_Documents id
              INNER JOIN Citizens c ON id.Citizen_ID = c.Citizen_ID
              LEFT JOIN Crimes cr ON c.Citizen_ID = cr.Citizen_ID
              WHERE id.Series = :series AND id.Number = :number
              GROUP BY c.Citizen_ID, id.Document_ID";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':series', $search_series, PDO::PARAM_STR);
    $stmt->bindParam(':number', $search_number, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<h2>Search Citizen by Document</h2>

<form method="POST" action="">
    <label>Document Series:</label><br>
    <input type="text" name="series" placeholder="Example: AB" value="<?= htmlspecialchars($search_series); ?>" required><br>
    <label>Document Number:</label><br>
    <input type="text" name="number" placeholder="Example: 123456" value="<?= htmlspecialchars($search_number); ?>" required><br>
    <button type="submit">Search</button>
</form>

<?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($results)): ?>
    <h3>Results:</h3>
    <table border="1">
        <tr>
            <th>Last Name</th>
            <th>First Name</th>
            <th>SSN</th>
            <th>Birth Date</th>
            <th>Document Series</th>
            <th>Document Number</th>
            <th>Total Crimes</th>
        </tr>
        <?php foreach ($results as $citizen): ?>
            <tr>
                <td><?= htmlspecialchars($citizen['Last_Name']); ?></td>
                <td><?= htmlspecialchars($citizen['First_Name']); ?></td>
                <td><?= htmlspecialchars($citizen['SSN']); ?></td>
                <td><?= htmlspecialchars($citizen['Birth_Date']); ?></td>
                <td><?= htmlspecialchars($citizen['Series']); ?></td>
                <td><?= htmlspecialchars($citizen['Number']); ?></td>
                <td><?= htmlspecialchars($citizen['Total_Crimes']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && empty($results)): ?>
    <p style="color: red;">No citizens were found with this document.</p>
<?php endif; ?>

<?php
include '../includes/footer.php';
?>
