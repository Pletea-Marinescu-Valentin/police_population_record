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

// Check if the document ID is provided in the URL
if (isset($_GET['id'])) {
    $document_id = $_GET['id'];

    // Delete the document from the database
    $delete_query = "DELETE FROM Identity_Documents WHERE Document_ID = :id";
    $delete_stmt = $conn->prepare($delete_query);
    $delete_stmt->bindParam(':id', $document_id, PDO::PARAM_INT);

    if ($delete_stmt->execute()) {
        echo "<p style='color: green;'>The document was successfully deleted!</p>";
    } else {
        echo "<p style='color: red;'>An error occurred while deleting the document.</p>";
    }
} else {
    echo "<p style='color: red;'>Document ID was not provided!</p>";
}
?>

<?php
include '../includes/footer.php';
?>
