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

    // Split the name into first and last name if there's a space
    $name_parts = explode(' ', $search_query);
    $last_name = $name_parts[0]; // First word
    $first_name = isset($name_parts[1]) ? $name_parts[1] : ''; // Second word, if it exists

    // Search the database with exact matching
    $query = "SELECT Citizens.Citizen_ID, Citizens.Last_Name, Citizens.First_Name, 
                 Citizens.SSN, Citizens.Birth_Date, 
                 CONCAT(Addresses.City, ', ', Addresses.Street, ' ', Addresses.Number) AS Address,
                 Identity_Documents.Series, Identity_Documents.Number
              FROM Citizens
              LEFT JOIN Addresses ON Citizens.Address_ID = Addresses.Address_ID
              LEFT JOIN Identity_Documents ON Citizens.Citizen_ID = Identity_Documents.Citizen_ID
              WHERE (Citizens.First_Name = :first_name AND Citizens.Last_Name = :last_name)
                OR (Citizens.Last_Name = :last_name AND :first_name = '')";

    $stmt = $conn->prepare($query);
    $stmt->bindValue(':last_name', $last_name, PDO::PARAM_STR);
    $stmt->bindValue(':first_name', $first_name, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<h2>Search Citizen</h2>

<!-- Search Form -->
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
                <th>SSN</th>
                <th>Birth Date</th>
                <th>Address</th>
            </tr>
            <?php foreach ($results as $citizen): ?>
                <tr>
                    <td><?= $citizen['Last_Name']; ?></td>
                    <td><?= $citizen['First_Name']; ?></td>
                    <td><?= $citizen['SSN']; ?></td>
                    <td><?= $citizen['Birth_Date']; ?></td>
                    <td><?= $citizen['Address']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p style="color: red;">No citizens found with the given name.</p>
    <?php endif; ?>
<?php endif; ?>

<?php
include '../includes/footer.php';
?>
