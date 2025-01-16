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
    // Fetch data from the form
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $rank = $_POST['rank'];

    // Insert officer into the database
    $query = "INSERT INTO Officers (Last_Name, First_Name, Rank) 
              VALUES (:last_name, :first_name, :rank)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':rank', $rank);

    if ($stmt->execute()) {
        echo "Officer added successfully!";
    } else {
        echo "An error occurred while adding the officer.";
    }
}
?>

<!-- HTML for the officer addition form -->
<form method="POST" action="">
    <h2>Add Officer</h2>
    <label>Last Name:</label>
    <input type="text" name="last_name" required><br>
    <label>First Name:</label>
    <input type="text" name="first_name" required><br>
    <label>Rank:</label>
    <input type="text" name="rank" required><br>
    <button type="submit">Add</button>
</form>

<?php
include '../includes/footer.php';
?>
