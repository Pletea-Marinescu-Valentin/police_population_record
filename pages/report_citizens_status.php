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

// Verificăm accesul pentru Admin și Officer
check_access(['Admin', 'Officer']);

// Obține statutul cetățenilor
$query = "
SELECT 
    c.Citizen_ID,
    c.Last_Name,
    c.First_Name,
    CASE 
        WHEN dl.License_ID IS NOT NULL THEN 'Da'
        ELSE 'Nu'
    END AS Has_License,
    COUNT(cr.Crime_ID) AS Total_Crimes
FROM 
    Citizens c
LEFT JOIN 
    Driving_Licenses dl ON c.Citizen_ID = dl.Citizen_ID
LEFT JOIN 
    Crimes cr ON c.Citizen_ID = cr.Citizen_ID
GROUP BY 
    c.Citizen_ID
ORDER BY 
    c.Last_Name, c.First_Name;
";

$stmt = $conn->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h2>Statut Cetățeni</h2>
<p>Acest raport afișează cetățenii, dacă au permis de conducere și numărul de crime asociate.</p>

<table border="1">
    <tr>
        <th>Nume</th>
        <th>Prenume</th>
        <th>Permis de Conducere</th>
        <th>Total Crime</th>
    </tr>
    <?php foreach ($results as $citizen): ?>
        <tr>
            <td><?= htmlspecialchars($citizen['Last_Name']); ?></td>
            <td><?= htmlspecialchars($citizen['First_Name']); ?></td>
            <td><?= htmlspecialchars($citizen['Has_License']); ?></td>
            <td><?= htmlspecialchars($citizen['Total_Crimes']); ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php
include '../includes/footer.php';
?>
