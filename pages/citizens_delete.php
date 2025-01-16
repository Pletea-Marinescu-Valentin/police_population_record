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

    $query = "DELETE FROM Citizens WHERE Citizen_ID = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $citizen_id);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>Citizen deleted successfully!</p>";
    } else {
        echo "<p style='color: red;'>An error occurred while deleting the citizen.</p>";
    }
} else {
    echo "<p style='color: red;'>Citizen ID not provided!</p>";
}
?>

<?php
include '../includes/footer.php';
?>
