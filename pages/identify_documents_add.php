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
    $citizen_name = trim($_POST['citizen_name']); // Full name of the citizen
    $series = trim($_POST['series']);
    $number = trim($_POST['number']);

    $errors = [];
    if (empty($citizen_name)) {
        $errors[] = "You must enter the full name of the citizen.";
    }
    if (empty($series)) {
        $errors[] = "Document series is required.";
    }
    if (empty($number)) {
        $errors[] = "Document number is required.";
    }

    // Retrieve Citizen_ID based on the provided name
    if (empty($errors)) {
        try {
            $name_parts = explode(' ', $citizen_name, 2);
            $last_name = $name_parts[0];
            $first_name = isset($name_parts[1]) ? $name_parts[1] : '';

            $query = "SELECT Citizen_ID FROM Citizens WHERE Last_Name = :last_name AND First_Name = :first_name";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->execute();
            $citizen = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$citizen) {
                $errors[] = "The provided citizen name does not exist.";
            } else {
                $citizen_id = $citizen['Citizen_ID'];
            }
        } catch (PDOException $e) {
            $errors[] = "Error verifying the citizen: " . $e->getMessage();
        }
    }

    if (empty($errors)) {
        try {
            $query = "INSERT INTO Identity_Documents (Citizen_ID, Series, Number) VALUES (:citizen_id, :series, :number)";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':citizen_id', $citizen_id, PDO::PARAM_INT);
            $stmt->bindParam(':series', $series, PDO::PARAM_STR);
            $stmt->bindParam(':number', $number, PDO::PARAM_STR);
            $stmt->execute();

            echo "<p style='color: green;'>The document was successfully added!</p>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Error adding the document: " . $e->getMessage() . "</p>";
        }
    } else {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }
}
?>

<h2>Add Identity Document</h2>

<form method="POST" action="">
    <label>Full name (Last Name First Name):</label><br>
    <input type="text" name="citizen_name" placeholder="Example: John Doe" required><br>

    <label>Document Series:</label><br>
    <input type="text" name="series" placeholder="Example: AB" required><br>

    <label>Document Number:</label><br>
    <input type="text" name="number" placeholder="Example: 123456" required><br>

    <button type="submit">Add Document</button>
</form>

<?php
include '../includes/footer.php';
?>
