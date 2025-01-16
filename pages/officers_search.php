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
    // Fetch the search query
    $search_query = trim($_POST['search_query']);

    // Split name into parts if there's a space
    $name_parts = explode(' ', $search_query);
    $last_name = $name_parts[0]; // First word
    $first_name = isset($name_parts[1]) ? $name_parts[1] : ''; // Second word if exists

    // Database search query
    $query = "SELECT Officers.Officer_ID, Officers.Last_Name, Officers.First_Name, Officers.Rank,
                     COUNT(Crimes.Crime_ID) AS Total_Cases
              FROM Officers
              LEFT JOIN Crimes ON Officers.Officer_ID = Crimes.Officer_ID
              WHERE (Officers.First_Name = :first_name AND Officers.Last_Name = :last_name)
                OR (Officers.Last_Name = :last_name AND :first_name = '')
                OR Officers.Rank LIKE :rank
              GROUP BY Officers.Officer_ID";
              
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':last_name', $last_name, PDO::PARAM_STR);
    $stmt->bindValue(':first_name', $first_name, PDO::PARAM_STR);
    $stmt->bindValue(':rank', '%' . $search_query . '%', PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<h2>Search Officer</h2>

<!-- Search Form -->
<form method="POST" action="">
    <label>Enter officer's full name or rank:</label><br>
    <input type="text" name="search_query" placeholder="Example: John Doe or Inspector" value="<?= htmlspecialchars($search_query); ?>" required>
    <button type="submit">Search</button>
</form>

<?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <h3>Results:</h3>
    <?php if (!empty($results)): ?>
        <table border="1">
            <tr>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Rank</th>
            </tr>
            <?php foreach ($results as $officer): ?>
                <tr>
                    <td><?= htmlspecialchars($officer['Last_Name']); ?></td>
                    <td><?= htmlspecialchars($officer['First_Name']); ?></td>
                    <td><?= htmlspecialchars($officer['Rank']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p style="color: red;">No officers found with the given criteria.</p>
    <?php endif; ?>
<?php endif; ?>

<?php
include '../includes/footer.php';
?>
