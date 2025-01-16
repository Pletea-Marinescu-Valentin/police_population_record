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

// Check if officer ID is provided in the URL
if (isset($_GET['id'])) {
    $officer_id = $_GET['id'];

    // Fetch officer details from the database
    $stmt = $conn->prepare("SELECT * FROM Officers WHERE Officer_ID = :id");
    $stmt->bindParam(':id', $officer_id);
    $stmt->execute();
    $officer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$officer) {
        echo "Officer not found!";
        exit;
    }

    // Handle the edit form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $last_name = $_POST['last_name'];
        $first_name = $_POST['first_name'];
        $rank = $_POST['rank'];

        $update_query = "UPDATE Officers SET Last_Name = :last_name, First_Name = :first_name, Rank = :rank 
                         WHERE Officer_ID = :id";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bindParam(':last_name', $last_name);
        $update_stmt->bindParam(':first_name', $first_name);
        $update_stmt->bindParam(':rank', $rank);
        $update_stmt->bindParam(':id', $officer_id);

        if ($update_stmt->execute()) {
            echo "Officer updated successfully!";
        } else {
            echo "An error occurred while updating the officer.";
        }
    }
}
?>

<!-- HTML for the officer edit form -->
<form method="POST" action="">
    <h2>Edit Officer</h2>
    <label>Last Name:</label>
    <input type="text" name="last_name" value="<?= $officer['Last_Name']; ?>" required><br>
    <label>First Name:</label>
    <input type="text" name="first_name" value="<?= $officer['First_Name']; ?>" required><br>
    <label>Rank:</label>
    <input type="text" name="rank" value="<?= $officer['Rank']; ?>" required><br>
    <button type="submit">Update</button>
</form>

<?php
include '../includes/footer.php';
?>
