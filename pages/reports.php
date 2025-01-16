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

// Check access for Admin and Officer roles
check_access(['Admin', 'Officer']);
?>

<h2>Reports</h2>
<p>Select one of the available reports:</p>

<div class="report-options">
    <!-- Reports about Citizens -->
    <h3>Citizen Reports</h3>
    <a href="report_citizens_status.php">Citizen Status (with/without licenses, with/without crimes)</a>
    <a href="report_citizens_crimes.php">Citizens and Their Crimes</a>

    <!-- Reports about Officers -->
    <h3>Officer Reports</h3>
    <a href="report_officers_cases.php">Officers and Investigated Cases</a>
    <a href="report_officer_most_crimes_year.php">Officer with Most Cases in a Year</a>
    <a href="report_top_officer_distinct_crime_types.php">Officers with the Most Diverse Crime Types</a>

    <!-- Reports about Licenses -->
    <h3>License Reports</h3>
    <a href="report_top_license_categories.php">Most Popular License Category</a>
    <a href="report_citizens_longest_license.php">Citizens with the Longest-Held Licenses</a>

    <!-- Reports about Crimes -->
    <h3>Crime Reports</h3>
    <a href="report_crimes_by_date.php">Crimes in a Specific Period</a>
    <a href="report_citizens_multiple_crimes.php">Citizens with More than 3 Crimes</a>

    <!-- Statistics -->
    <h3>Statistics</h3>
    <a href="statistics.php">Statistics</a>

</div>

<?php
include '../includes/footer.php';
?>
