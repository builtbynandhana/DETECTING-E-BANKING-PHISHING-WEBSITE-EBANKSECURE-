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

// Get the ID from the POST request
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

// Prepare the SQL statement to delete the URL
$sql = "DELETE FROM user_reported_urls WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

$response = [];
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['error'] = $conn->error;
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);

// Close the connection
$stmt->close();
$conn->close();
?>
