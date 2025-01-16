<?php
$host = "localhost";
$db_name = "policepopulationrecord";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Failed connection: " . $e->getMessage();
    exit;
}
?>
