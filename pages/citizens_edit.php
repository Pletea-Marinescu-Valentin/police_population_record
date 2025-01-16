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
    $citizen_id = $_GET['id'];

    // Fetch citizen data
    $stmt = $conn->prepare("SELECT * FROM Citizens WHERE Citizen_ID = :id");
    $stmt->bindParam(':id', $citizen_id);
    $stmt->execute();
    $citizen = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $last_name = $_POST['last_name'];
        $first_name = $_POST['first_name'];
        $ssn = $_POST['ssn'];
        $birth_date = $_POST['birth_date'];
        $address_id = $_POST['address_id'];

        $query = "UPDATE Citizens SET Last_Name = :last_name, First_Name = :first_name, SSN = :ssn, 
                  Birth_Date = :birth_date, Address_ID = :address_id WHERE Citizen_ID = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':ssn', $ssn);
        $stmt->bindParam(':birth_date', $birth_date);
        $stmt->bindParam(':address_id', $address_id);
        $stmt->bindParam(':id', $citizen_id);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Citizen updated successfully!</p>";
        } else {
            echo "<p style='color: red;'>An error occurred while updating the citizen.</p>";
        }
    }
}
?>

<h2>Edit Citizen</h2>
<form method="POST" action="">
    <label>Last Name:</label>
    <input type="text" name="last_name" value="<?= $citizen['Last_Name']; ?>" required><br>
    <label>First Name:</label>
    <input type="text" name="first_name" value="<?= $citizen['First_Name']; ?>" required><br>
    <label>SSN:</label>
    <input type="text" name="ssn" value="<?= $citizen['SSN']; ?>" required><br>
    <label>Date of Birth:</label>
    <input type="date" name="birth_date" value="<?= $citizen['Birth_Date']; ?>" required><br>
    <label>Address ID:</label>
    <input type="number" name="address_id" value="<?= $citizen['Address_ID']; ?>" required><br>
    <button type="submit">Update</button>
</form>

<?php
include '../includes/footer.php';
?>
