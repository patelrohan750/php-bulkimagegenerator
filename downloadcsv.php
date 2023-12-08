<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "scraper";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to select all records from your table
$sql = "SELECT question FROM questions"; // Replace 'your_table' with your actual table name

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Set headers to force download as a CSV file
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=exported_records.csv');

    // Create a file pointer connected to the output stream
    $output = fopen('php://output', 'w');

    // Output CSV column headers based on your table structure
    fputcsv($output, array('Name')); // Replace with your column names

    // Output each row's data
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    // Close file pointer
    fclose($output);
} else {
    echo "No records found";
}

// Close the database connection
$conn->close();
?>
