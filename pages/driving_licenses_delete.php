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

    // Delete the license from the database
    $delete_query = "DELETE FROM Driving_Licenses WHERE License_ID = :id";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bindParam(':id', $license_id);

    if ($delete_stmt->execute()) {
        echo "The driving license was successfully deleted!";
    } else {
        echo "An error occurred while deleting the driving license.";
    }
} else {
    echo "The license ID was not provided!";
}
?>

<?php
include '../includes/footer.php';
?>
