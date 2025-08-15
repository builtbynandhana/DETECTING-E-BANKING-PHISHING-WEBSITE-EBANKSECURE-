<?php
$servername = "localhost";
$username = "root";
$password = "pass123";
$db="phishing_detection";

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "New record created successfully";

    } else {
        echo "Error: " . $stmt->error;
    }
    if ($row['role']) {
        header("Location: admin_dash.html");
    } else {
        header("Location:userhome.html");
    }

    $stmt->close();
    $conn->close();
}



