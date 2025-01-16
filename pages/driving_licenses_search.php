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
    // Retrieve the search text
    $search_query = trim($_POST['search_query']);

    // Split name into parts if there is a space
    $name_parts = explode(' ', $search_query);
    $last_name = $name_parts[0]; // First word
    $first_name = isset($name_parts[1]) ? $name_parts[1] : ''; // Second word, if it exists

    // Search the database
    $query = "SELECT dl.License_ID, dl.Category, dl.Issue_Date, dl.Expiry_Date,
                     c.Last_Name, c.First_Name, 
                     CONCAT(a.City, ', ', a.Street, ' ', a.Number) AS Address
              FROM Driving_Licenses dl
              INNER JOIN Citizens c ON dl.Citizen_ID = c.Citizen_ID
              LEFT JOIN Addresses a ON c.Address_ID = a.Address_ID
              WHERE (c.First_Name = :first_name AND c.Last_Name = :last_name)
                OR (c.Last_Name = :last_name AND :first_name = '')";

    $stmt = $conn->prepare($query);
    $stmt->bindValue(':last_name', $last_name, PDO::PARAM_STR);
    $stmt->bindValue(':first_name', $first_name, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<h2>Search Driving License</h2>

<form method="POST" action="">
    <label>Enter the citizen's full name:</label><br>
    <input type="text" name="search_query" placeholder="Example: John Doe" value="<?= htmlspecialchars($search_query); ?>" required>
    <button type="submit">Search</button>
</form>

<?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <h3>Results:</h3>
    <?php if (!empty($results)): ?>
        <table border="1">
            <tr>
                <th>Last Name</th>
                <th>First Name</th>
                <th>Category</th>
                <th>Issue Date</th>
                <th>Expiry Date</th>
                <th>Address</th>
            </tr>
            <?php foreach ($results as $license): ?>
                <tr>
                    <td><?= htmlspecialchars($license['Last_Name']); ?></td>
                    <td><?= htmlspecialchars($license['First_Name']); ?></td>
                    <td><?= htmlspecialchars($license['Category']); ?></td>
                    <td><?= htmlspecialchars($license['Issue_Date']); ?></td>
                    <td><?= htmlspecialchars($license['Expiry_Date']); ?></td>
                    <td><?= htmlspecialchars($license['Address']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p style="color: red;">No licenses found for this citizen.</p>
    <?php endif; ?>
<?php endif; ?>

<?php
include '../includes/footer.php';
?>
