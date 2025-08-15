<?php
$servername = "localhost";
$username = "root";
$password = "pass123";
$db = "phishing_detection";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the JSON input
$data = json_decode(file_get_contents("php://input"), true);
$reported_url = $data['reported_url'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO phishing (url, if_phish) VALUES (?, 1)"); // Assuming 1 for phishing
$stmt->bind_param("s", $reported_url);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
