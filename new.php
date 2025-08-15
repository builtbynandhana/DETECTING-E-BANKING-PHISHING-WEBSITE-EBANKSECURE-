<?php
// Database configuration
$servername = "localhost"; // Change if necessary
$username = "root"; // Your database username
$password = "pass123"; // Your database password
$dbname = "phishing_detection"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$url = $_POST['url']; // Assuming you get the URL from a form
$query = "INSERT INTO url_check_history (url) VALUES ('$url')";
$conn->query($query);


// Function to check if URL exists and its phishing status
function checkUrlStatus($conn, $url) {
    $stmt = $conn->prepare("SELECT if_phish FROM urlsnew WHERE url = ?");
    $stmt->bind_param("s", $url);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['if_phish'];
    }
    return null; // URL not found
}

// Get the URL from the form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $urlToCheck = $_POST['url'];
    $status = checkUrlStatus($conn, $urlToCheck);

    if ($status !== null) {
        if ($status == 1) {
            $message = "The URL is present in the database and it is a phishing site.";
        } else {
            $message = "The URL is present in the database and it is legitimate.";
        }
    } else {
        $message = "The URL is not present in the database.";
    }

    // Redirect back to the form with the message
    header("Location: urlsearch.php?message=" . urlencode($message));
    exit();
}

$conn->close();
