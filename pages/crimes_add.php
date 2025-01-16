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

// Retrieve citizens and officers for selection
$citizens = $conn->query("SELECT Citizen_ID, Last_Name, First_Name FROM Citizens")->fetchAll(PDO::FETCH_ASSOC);
$officers = $conn->query("SELECT Officer_ID, Last_Name, First_Name FROM Officers")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    $crime_date = $_POST['crime_date'];
    $citizen_id = $_POST['citizen_id'];
    $officer_id = $_POST['officer_id'];

    // Insert crime into the database
    $query = "INSERT INTO Crimes (Description, Crime_Date, Citizen_ID, Officer_ID) 
              VALUES (:description, :crime_date, :citizen_id, :officer_id)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':crime_date', $crime_date);
    $stmt->bindParam(':citizen_id', $citizen_id);
    $stmt->bindParam(':officer_id', $officer_id);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Crime added successfully!</p>";
    } else {
        echo "<p style='color: red;'>An error occurred while adding the crime.</p>";
    }
}
?>

<h2>Add Crime</h2>
<form method="POST" action="">
    <label>Description:</label>
    <textarea name="description" required></textarea><br>
    <label>Date:</label>
    <input type="date" name="crime_date" required><br>
    <label>Citizen:</label>
    <select name="citizen_id" required>
        <?php foreach ($citizens as $citizen): ?>
            <option value="<?= $citizen['Citizen_ID']; ?>"><?= $citizen['Last_Name'] . " " . $citizen['First_Name']; ?></option>
        <?php endforeach; ?>
    </select><br>
    <label>Officer:</label>
    <select name="officer_id" required>
        <?php foreach ($officers as $officer): ?>
            <option value="<?= $officer['Officer_ID']; ?>"><?= $officer['Last_Name'] . " " . $officer['First_Name']; ?></option>
        <?php endforeach; ?>
    </select><br>
    <button type="submit">Add</button>
</form>

<?php
include '../includes/footer.php';
?>
