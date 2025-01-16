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

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p style='color: red;'>Invalid document ID.</p>";
    include '../includes/footer.php';
    exit;
}

$document_id = $_GET['id'];

$query = "SELECT Identity_Documents.Document_ID, Identity_Documents.Series, Identity_Documents.Number, 
                 Citizens.Citizen_ID, CONCAT(Citizens.First_Name, ' ', Citizens.Last_Name) AS Citizen_Name
          FROM Identity_Documents
          INNER JOIN Citizens ON Identity_Documents.Citizen_ID = Citizens.Citizen_ID
          WHERE Identity_Documents.Document_ID = :document_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':document_id', $document_id, PDO::PARAM_INT);
$stmt->execute();
$document = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$document) {
    echo "<p style='color: red;'>The document was not found.</p>";
    include '../includes/footer.php';
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $series = trim($_POST['series']);
    $number = trim($_POST['number']);

    $errors = [];
    if (empty($series)) {
        $errors[] = "Document series is required.";
    }
    if (empty($number)) {
        $errors[] = "Document number is required.";
    }

    if (empty($errors)) {
        try {
            $query = "UPDATE Identity_Documents 
                      SET Series = :series, Number = :number 
                      WHERE Document_ID = :document_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':series', $series, PDO::PARAM_STR);
            $stmt->bindParam(':number', $number, PDO::PARAM_STR);
            $stmt->bindParam(':document_id', $document_id, PDO::PARAM_INT);
            $stmt->execute();

            echo "<p style='color: green;'>The document was successfully updated!</p>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Error updating the document: " . $e->getMessage() . "</p>";
        }
    } else {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }
}
?>

<h2>Edit Identity Document</h2>

<form method="POST" action="">
    <label>Citizen:</label><br>
    <input type="text" value="<?= htmlspecialchars($document['Citizen_Name']); ?>" disabled><br>

    <label>Document Series:</label><br>
    <input type="text" name="series" value="<?= htmlspecialchars($document['Series']); ?>" required><br>

    <label>Document Number:</label><br>
    <input type="text" name="number" value="<?= htmlspecialchars($document['Number']); ?>" required><br>

    <button type="submit">Update Document</button>
</form>

<?php
include '../includes/footer.php';
?>
