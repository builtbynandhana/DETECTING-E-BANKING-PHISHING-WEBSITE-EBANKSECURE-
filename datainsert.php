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

// Path to your CSV file
$csvFile = 'C:/wamp64/www/AI/legitmate.csv';

// Open the CSV file
if (($handle = fopen($csvFile, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        // $data[0] is the first (and only) column in your CSV
        $url = $data[0];

        // Prepare SQL query to insert into urls_table
        $stmt = $conn->prepare("INSERT INTO phishing (url) VALUES (?)");
        $stmt->bind_param("s", $url);

        // Execute query
        if ($stmt->execute()) {
            echo "URL inserted successfully: " . $url . "<br>";
        } else {
            echo "Error inserting URL: " . $url . "<br>";
        }
    }
    // Close the CSV file
    fclose($handle);
} else {
    echo "Error opening CSV file.";
}

// Close the database connection
$conn->close();
?>
