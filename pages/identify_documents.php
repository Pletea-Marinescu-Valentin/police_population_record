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

check_access(['Admin', 'Officer']);

$query = "SELECT Identity_Documents.Document_ID, Identity_Documents.Series, Identity_Documents.Number, 
                 Citizens.Citizen_ID, CONCAT(Citizens.First_Name, ' ', Citizens.Last_Name) AS Citizen_Name
          FROM Identity_Documents
          INNER JOIN Citizens ON Identity_Documents.Citizen_ID = Citizens.Citizen_ID";
$stmt = $conn->query($query);
$documents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>List of Identity Documents</h2>
<?php if ($_SESSION['role'] === 'Admin'): ?>
    <a href="identify_documents_add.php">Add Document</a>
<?php endif; ?>
<a href="identify_documents_search.php">Search Document</a>
<table border="1">
    <tr>
        <th>Series</th>
        <th>Number</th>
        <th>Citizen</th>
        <?php if ($_SESSION['role'] === 'Admin'): ?>
            <th>Actions</th>
        <?php endif; ?>
    </tr>
    <?php foreach ($documents as $document): ?>
        <tr>
            <td><?= htmlspecialchars($document['Series']); ?></td>
            <td><?= htmlspecialchars($document['Number']); ?></td>
            <td><?= htmlspecialchars($document['Citizen_Name']); ?></td>
            <?php if ($_SESSION['role'] === 'Admin'): ?>
                <td>
                    <a href="identify_documents_edit.php?id=<?= $document['Document_ID']; ?>">Edit</a> |
                    <a href="identify_documents_delete.php?id=<?= $document['Document_ID']; ?>" onclick="return confirm('Are you sure you want to delete this document?');">Delete</a>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
</table>

<?php
include '../includes/footer.php';
?>
