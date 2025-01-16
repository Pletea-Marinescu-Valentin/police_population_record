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

// Retrieve citizens for selection
$citizens = $conn->query("SELECT Citizen_ID, Last_Name, First_Name FROM Citizens")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];
    $issue_date = $_POST['issue_date'];
    $expiry_date = $_POST['expiry_date'];
    $citizen_id = $_POST['citizen_id'];

    // Insert license into database
    $query = "INSERT INTO Driving_Licenses (Category, Issue_Date, Expiry_Date, Citizen_ID) 
              VALUES (:category, :issue_date, :expiry_date, :citizen_id)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':issue_date', $issue_date);
    $stmt->bindParam(':expiry_date', $expiry_date);
    $stmt->bindParam(':citizen_id', $citizen_id);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>License added successfully!</p>";
    } else {
        echo "<p style='color: red;'>An error occurred while adding the license.</p>";
    }
}
?>

<h2>Add Driving License</h2>
<form method="POST" action="">
    <label>Category:</label>
    <input type="text" name="category" required><br>
    <label>Issue Date:</label>
    <input type="date" name="issue_date" required><br>
    <label>Expiry Date:</label>
    <input type="date" name="expiry_date" required><br>
    <label>Citizen:</label>
    <select name="citizen_id" required>
        <?php foreach ($citizens as $citizen): ?>
            <option value="<?= $citizen['Citizen_ID']; ?>"><?= htmlspecialchars($citizen['Last_Name'] . " " . $citizen['First_Name']); ?></option>
        <?php endforeach; ?>
    </select><br>
    <button type="submit">Add</button>
</form>

<?php
include '../includes/footer.php';
?>
