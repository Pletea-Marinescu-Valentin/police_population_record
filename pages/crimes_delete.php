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

    $query = "DELETE FROM Crimes WHERE Crime_ID = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $crime_id);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Crime deleted successfully!</p>";
    } else {
        echo "<p style='color: red;'>An error occurred while deleting the crime.</p>";
    }
} else {
    echo "<p style='color: red;'>Crime ID not provided!</p>";
}
?>

<?php
include '../includes/footer.php';
?>
