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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $ssn = $_POST['ssn'];
    $birth_date = $_POST['birth_date'];
    $address_id = $_POST['address_id'];

    // Insert citizen into database
    $query = "INSERT INTO Citizens (Last_Name, First_Name, SSN, Birth_Date, Address_ID) 
              VALUES (:last_name, :first_name, :ssn, :birth_date, :address_id)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':ssn', $ssn);
    $stmt->bindParam(':birth_date', $birth_date);
    $stmt->bindParam(':address_id', $address_id);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Citizen added successfully!</p>";
    } else {
        echo "<p style='color: red;'>An error occurred while adding the citizen.</p>";
    }
}
?>

<h2>Add Citizen</h2>
<form method="POST" action="">
    <label>Last Name:</label>
    <input type="text" name="last_name" required><br>
    <label>First Name:</label>
    <input type="text" name="first_name" required><br>
    <label>SSN:</label>
    <input type="text" name="ssn" required><br>
    <label>Date of Birth:</label>
    <input type="date" name="birth_date" required><br>
    <label>Address ID:</label>
    <input type="number" name="address_id" required><br>
    <button type="submit">Add</button>
</form>

<?php
include '../includes/footer.php';
?>
