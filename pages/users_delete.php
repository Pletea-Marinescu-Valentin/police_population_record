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
include '../includes/functions.php';

// Only Admins are allowed access
check_access(['Admin']);

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM Users WHERE User_ID = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<p style='color: green;'>User deleted successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error deleting user.</p>";
    }
}
?>

<?php
include '../includes/footer.php';
?>
