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
$search_query = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_query = trim($_POST['search_query']);

    // Split search query into parts
    $name_parts = explode(' ', $search_query);
    $last_name = $name_parts[0];
    $first_name = isset($name_parts[1]) ? $name_parts[1] : '';

    // Query crimes based on citizen names
    $query = "SELECT c.Crime_ID, c.Description, c.Crime_Date,
                     CONCAT(ci.Last_Name, ' ', ci.First_Name) AS Citizen_Name,
                     CONCAT(o.Last_Name, ' ', o.First_Name) AS Officer_Name
              FROM Crimes c
              INNER JOIN Citizens ci ON c.Citizen_ID = ci.Citizen_ID
              INNER JOIN Officers o ON c.Officer_ID = o.Officer_ID
              WHERE ci.Last_Name LIKE :last_name
                 OR ci.First_Name LIKE :first_name
                 OR CONCAT(ci.Last_Name, ' ', ci.First_Name) LIKE :full_name";

    $stmt = $conn->prepare($query);
    $stmt->bindValue(':last_name', '%' . $last_name . '%', PDO::PARAM_STR);
    $stmt->bindValue(':first_name', '%' . $first_name . '%', PDO::PARAM_STR);
    $stmt->bindValue(':full_name', '%' . $search_query . '%', PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<h2>Search Crimes</h2>
<form method="POST" action="">
    <label>Enter citizen's full name (e.g., Smith John):</label><br>
    <input type="text" name="search_query" placeholder="Example: Smith John" value="<?= htmlspecialchars($search_query); ?>" required>
    <button type="submit">Search</button>
</form>

<?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <h3>Results:</h3>
    <?php if (!empty($results)): ?>
        <table border="1">
            <tr>
                <th>Description</th>
                <th>Date</th>
                <th>Citizen</th>
                <th>Officer</th>
            </tr>
            <?php foreach ($results as $crime): ?>
                <tr>
                    <td><?= htmlspecialchars($crime['Description']); ?></td>
                    <td><?= htmlspecialchars($crime['Crime_Date']); ?></td>
                    <td><?= htmlspecialchars($crime['Citizen_Name']); ?></td>
                    <td><?= htmlspecialchars($crime['Officer_Name']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p style="color: red;">No crimes found for the given citizen.</p>
    <?php endif; ?>
<?php endif; ?>

<?php
include '../includes/footer.php';
?>
