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

// Get the number of citizens per city
$query_citizens_per_city = "SELECT Addresses.City, COUNT(Citizens.Citizen_ID) AS Total
                            FROM Citizens
                            LEFT JOIN Addresses ON Citizens.Address_ID = Addresses.Address_ID
                            GROUP BY Addresses.City";
$stmt = $conn->query($query_citizens_per_city);
$citizens_per_city = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the distribution of license categories
$query_license_categories = "SELECT Category, COUNT(License_ID) AS Total
                             FROM Driving_Licenses
                             GROUP BY Category";
$stmt = $conn->query($query_license_categories);
$license_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the number of cases per officer
$query_cases_per_officer = "SELECT Officers.Last_Name, COUNT(Crimes.Crime_ID) AS Total
                            FROM Crimes
                            LEFT JOIN Officers ON Crimes.Officer_ID = Officers.Officer_ID
                            GROUP BY Officers.Officer_ID";
$stmt = $conn->query($query_cases_per_officer);
$cases_per_officer = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the number of crimes per month
$query_crimes_by_month = "SELECT DATE_FORMAT(Crime_Date, '%Y-%m') AS Month, COUNT(Crime_ID) AS Total
                          FROM Crimes
                          GROUP BY DATE_FORMAT(Crime_Date, '%Y-%m')";
$stmt = $conn->query($query_crimes_by_month);
$crimes_by_month = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Statistics</h2>
<div>
    <a href="generate_pdf.php" target="_blank">Export Report as PDF</a> |
    <a href="generate_csv.php" target="_blank">Export Report as CSV</a>
</div>

<div>
    <canvas id="citizensPerCityChart"></canvas>
    <canvas id="licenseCategoriesChart"></canvas>
    <canvas id="casesPerOfficerChart"></canvas>
    <canvas id="crimesByMonthChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Prepare data for charts

// Number of citizens per city
const citizensPerCityData = {
    labels: <?= json_encode(array_column($citizens_per_city, 'City')) ?>,
    datasets: [{
        label: 'Number of Citizens',
        data: <?= json_encode(array_column($citizens_per_city, 'Total')) ?>,
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
    }]
};

// Distribution of license categories
const licenseCategoriesData = {
    labels: <?= json_encode(array_column($license_categories, 'Category')) ?>,
    datasets: [{
        label: 'License Categories',
        data: <?= json_encode(array_column($license_categories, 'Total')) ?>,
        backgroundColor: 'rgba(153, 102, 255, 0.2)',
        borderColor: 'rgba(153, 102, 255, 1)',
        borderWidth: 1
    }]
};

// Number of cases per officer
const casesPerOfficerData = {
    labels: <?= json_encode(array_column($cases_per_officer, 'Last_Name')) ?>,
    datasets: [{
        label: 'Cases',
        data: <?= json_encode(array_column($cases_per_officer, 'Total')) ?>,
        backgroundColor: 'rgba(255, 206, 86, 0.2)',
        borderColor: 'rgba(255, 206, 86, 1)',
        borderWidth: 1
    }]
};

// Number of crimes per month
const crimesByMonthData = {
    labels: <?= json_encode(array_column($crimes_by_month, 'Month')) ?>,
    datasets: [{
        label: 'Crimes',
        data: <?= json_encode(array_column($crimes_by_month, 'Total')) ?>,
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
    }]
};

// Generate charts
new Chart(document.getElementById('citizensPerCityChart'), {
    type: 'bar',
    data: citizensPerCityData
});

new Chart(document.getElementById('licenseCategoriesChart'), {
    type: 'pie',
    data: licenseCategoriesData
});

new Chart(document.getElementById('casesPerOfficerChart'), {
    type: 'bar',
    data: casesPerOfficerData
});

new Chart(document.getElementById('crimesByMonthChart'), {
    type: 'line',
    data: crimesByMonthData
});
</script>

<?php
include '../includes/footer.php';
?>
