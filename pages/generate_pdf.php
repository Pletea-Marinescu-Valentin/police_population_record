<?php
require('../fpdf/fpdf.php');
include '../config/database.php';

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Crime Report', 0, 1, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

// Retrieve data for the report
$query = "SELECT Crimes.Description, Crimes.Crime_Date, 
                 CONCAT(Citizens.Last_Name, ' ', Citizens.First_Name) AS Citizen_Name
          FROM Crimes
          INNER JOIN Citizens ON Crimes.Citizen_ID = Citizens.Citizen_ID";
$stmt = $conn->query($query);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($data as $row) {
    $pdf->Cell(0, 10, "Citizen: {$row['Citizen_Name']} | Crime: {$row['Description']} | Date: {$row['Crime_Date']}", 0, 1);
}

$pdf->Output('D', 'crime_report.pdf');
?>
