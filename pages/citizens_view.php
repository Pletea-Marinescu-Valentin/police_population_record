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

// Only Officer and Admin can access
check_access(['Admin', 'Officer']);

// Check if the citizen ID is provided in the URL
if (isset($_GET['id'])) {
    $citizen_id = $_GET['id'];

    // Retrieve details about the citizen
    $query = "SELECT Citizens.*, 
                     CONCAT(Addresses.City, ', ', Addresses.Street, ' ', Addresses.Number) AS Address
              FROM Citizens
              LEFT JOIN Addresses ON Citizens.Address_ID = Addresses.Address_ID
              WHERE Citizen_ID = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $citizen_id, PDO::PARAM_INT);
    $stmt->execute();
    $citizen = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$citizen) {
        echo "<p style='color: red;'>The citizen was not found.</p>";
        include '../includes/footer.php';
        exit;
    }
} else {
    echo "<p style='color: red;'>Citizen ID was not provided.</p>";
    include '../includes/footer.php';
    exit;
}
?>

<h2>Citizen Details</h2>
<p><strong>Last Name:</strong> <?= htmlspecialchars($citizen['Last_Name']); ?></p>
<p><strong>First Name:</strong> <?= htmlspecialchars($citizen['First_Name']); ?></p>
<p><strong>Birth Date:</strong> <?= htmlspecialchars($citizen['Birth_Date']); ?></p>
<p><strong>Address:</strong> <?= htmlspecialchars($citizen['Address']); ?></p>

<?php
include '../includes/footer.php';
?>
