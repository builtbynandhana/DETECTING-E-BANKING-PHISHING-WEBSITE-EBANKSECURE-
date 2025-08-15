<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "pass123";
$dbname = "phishing_detection";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Path to your new CSV file
$newCsvFile = 'C:/wamp64/www/AI/verified_online.csv';

// Open the new CSV file
if (($handle = fopen($newCsvFile, "r")) !== FALSE) {
    $rowCount = 0; // Initialize row counter
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $rowCount++;
        
        // Skip the header row and any rows beyond A85201
        if ($rowCount < 2 || $rowCount > 85201) {
            continue; // Skip this iteration
        }

        // $data[0] is the first column in your CSV
        $url = $data[0];
        $if_phish = 1;  // Set the if_phish value to 1

        // Prepare SQL query to insert into urls_table
        $stmt = $conn->prepare("INSERT INTO phishing (url, if_phish) VALUES (?, ?)");
        $stmt->bind_param("si", $url, $if_phish);  // "si" stands for string and integer

        // Execute query
        if ($stmt->execute()) {
            echo "URL inserted successfully: " . $url . "<br>";
        } else {
            echo "Error inserting URL: " . $url . "<br>";
        }
    }
    // Close the new CSV file
    fclose($handle);
} else {
    echo "Error opening new CSV file.";
}

// Close the database connection
$conn->close();
?>
