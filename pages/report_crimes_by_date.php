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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $query = "SELECT Crime_ID, Description, Crime_Date
              FROM Crimes
              WHERE Crime_Date BETWEEN :start_date AND :end_date";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<h2>Report: Crimes in a Specific Period</h2>

<form method="POST" action="">
    <label>Start Date:</label>
    <input type="date" name="start_date" required>
    <label>End Date:</label>
    <input type="date" name="end_date" required>
    <button type="submit">Search</button>
</form>

<?php if (!empty($results)): ?>
    <table border="1">
        <tr>
            <th>Description</th>
            <th>Crime Date</th>
        </tr>
        <?php foreach ($results as $crime): ?>
            <tr>
                <td><?= htmlspecialchars($crime['Description']); ?></td>
                <td><?= htmlspecialchars($crime['Crime_Date']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <p>No crimes found in the selected period.</p>
<?php endif; ?>

<?php
include '../includes/footer.php';
?>
