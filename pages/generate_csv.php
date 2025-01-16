<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=crime_report.csv');

include '../config/database.php';

// Retrieve data for the report
$query = "SELECT Crimes.Description, Crimes.Crime_Date, 
                 CONCAT(Citizens.Last_Name, ' ', Citizens.First_Name) AS Citizen_Name
          FROM Crimes
          INNER JOIN Citizens ON Crimes.Citizen_ID = Citizens.Citizen_ID";
$stmt = $conn->query($query);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Write data to CSV
$output = fopen('php://output', 'w');
fputcsv($output, ['Citizen', 'Crime', 'Date']);
foreach ($data as $row) {
    fputcsv($output, [$row['Citizen_Name'], $row['Description'], $row['Crime_Date']]);
}
fclose($output);
?>
