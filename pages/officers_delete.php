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

    // Delete officer from the database
    $delete_query = "DELETE FROM Officers WHERE Officer_ID = :id";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bindParam(':id', $officer_id);

    if ($delete_stmt->execute()) {
        echo "Officer deleted successfully!";
    } else {
        echo "An error occurred while deleting the officer.";
    }
} else {
    echo "Officer ID was not provided!";
}
?>

<?php
include '../includes/footer.php';
?>
