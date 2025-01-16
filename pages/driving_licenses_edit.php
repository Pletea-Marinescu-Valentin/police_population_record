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

// Check if the license ID is provided in the URL
if (isset($_GET['id'])) {
    $license_id = $_GET['id'];

    // Retrieve license details from the database
    $stmt = $conn->prepare("SELECT * FROM Driving_Licenses WHERE License_ID = :id");
    $stmt->bindParam(':id', $license_id);
    $stmt->execute();
    $license = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$license) {
        echo "The driving license was not found!";
        exit;
    }

    // Retrieve citizens for selection
    $citizens = $conn->query("SELECT Citizen_ID, Last_Name, First_Name FROM Citizens")->fetchAll(PDO::FETCH_ASSOC);

    // Handle the form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $category = $_POST['category'];
        $issue_date = $_POST['issue_date'];
        $expiry_date = $_POST['expiry_date'];
        $citizen_id = $_POST['citizen_id'];

        $update_query = "UPDATE Driving_Licenses SET Category = :category, Issue_Date = :issue_date, Expiry_Date = :expiry_date, Citizen_ID = :citizen_id WHERE License_ID = :id";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bindParam(':category', $category);
        $update_stmt->bindParam(':issue_date', $issue_date);
        $update_stmt->bindParam(':expiry_date', $expiry_date);
        $update_stmt->bindParam(':citizen_id', $citizen_id);
        $update_stmt->bindParam(':id', $license_id);

        if ($update_stmt->execute()) {
            echo "The driving license was successfully updated!";
        } else {
            echo "An error occurred while updating the driving license.";
        }
    }
}
?>

<!-- HTML for the driving license edit form -->
<form method="POST" action="">
    <h2>Edit Driving License</h2>
    <label>Category:</label>
    <input type="text" name="category" value="<?= $license['Category']; ?>" required><br>
    <label>Issue Date:</label>
    <input type="date" name="issue_date" value="<?= $license['Issue_Date']; ?>" required><br>
    <label>Expiry Date:</label>
    <input type="date" name="expiry_date" value="<?= $license['Expiry_Date']; ?>" required><br>
    <label>Citizen:</label>
    <select name="citizen_id" required>
        <?php foreach ($citizens as $citizen): ?>
            <option value="<?= $citizen['Citizen_ID']; ?>" <?= $license['Citizen_ID'] == $citizen['Citizen_ID'] ? 'selected' : ''; ?>>
                <?= $citizen['Last_Name'] . " " . $citizen['First_Name']; ?>
            </option>
        <?php endforeach; ?>
    </select><br>
    <button type="submit">Update</button>
</form>

<?php
include '../includes/footer.php';
?>
