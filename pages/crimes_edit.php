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

if (isset($_GET['id'])) {
    $crime_id = $_GET['id'];

    // Fetch crime details
    $stmt = $conn->prepare("SELECT * FROM Crimes WHERE Crime_ID = :id");
    $stmt->bindParam(':id', $crime_id);
    $stmt->execute();
    $crime = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$crime) {
        echo "Crime not found!";
        exit;
    }

    // Fetch citizens and officers for selection
    $citizens = $conn->query("SELECT Citizen_ID, Last_Name, First_Name FROM Citizens")->fetchAll(PDO::FETCH_ASSOC);
    $officers = $conn->query("SELECT Officer_ID, Last_Name, First_Name FROM Officers")->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $description = $_POST['description'];
        $crime_date = $_POST['crime_date'];
        $citizen_id = $_POST['citizen_id'];
        $officer_id = $_POST['officer_id'];

        $query = "UPDATE Crimes SET Description = :description, Crime_Date = :crime_date, 
                  Citizen_ID = :citizen_id, Officer_ID = :officer_id WHERE Crime_ID = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':crime_date', $crime_date);
        $stmt->bindParam(':citizen_id', $citizen_id);
        $stmt->bindParam(':officer_id', $officer_id);
        $stmt->bindParam(':id', $crime_id);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Crime updated successfully!</p>";
        } else {
            echo "<p style='color: red;'>An error occurred while updating the crime.</p>";
        }
    }
}
?>

<h2>Edit Crime</h2>
<form method="POST" action="">
    <label>Description:</label>
    <textarea name="description" required><?= htmlspecialchars($crime['Description']); ?></textarea><br>
    <label>Date:</label>
    <input type="date" name="crime_date" value="<?= htmlspecialchars($crime['Crime_Date']); ?>" required><br>
    <label>Citizen:</label>
    <select name="citizen_id" required>
        <?php foreach ($citizens as $citizen): ?>
            <option value="<?= $citizen['Citizen_ID']; ?>" <?= $crime['Citizen_ID'] == $citizen['Citizen_ID'] ? 'selected' : ''; ?>>
                <?= $citizen['Last_Name'] . " " . $citizen['First_Name']; ?>
            </option>
        <?php endforeach; ?>
    </select><br>
    <label>Officer:</label>
    <select name="officer_id" required>
        <?php foreach ($officers as $officer): ?>
            <option value="<?= $officer['Officer_ID']; ?>" <?= $crime['Officer_ID'] == $officer['Officer_ID'] ? 'selected' : ''; ?>>
                <?= $officer['Last_Name'] . " " . $officer['First_Name']; ?>
            </option>
        <?php endforeach; ?>
    </select><br>
    <button type="submit">Update</button>
</form>

<?php
include '../includes/footer.php';
?>
