<?php
$servername = "localhost";
$username = "root";
$password = "pass123";
$db="phishing_detection";
// Create connection
$conn = new mysqli($servername, $username, $password,$db);



// Fetch total users
$total_users_query = "SELECT COUNT(*) as total FROM user";
$total_users_result = $conn->query($total_users_query);
$total_users = $total_users_result->fetch_assoc()['total'];

// Fetch active users
$active_users_query = "SELECT COUNT(*) as active FROM user WHERE role=0";
$active_users_result = $conn->query($active_users_query);
$active_users = $active_users_result->fetch_assoc()['active'];

// Fetch total URLs
$total_urls_query = "SELECT COUNT(*) as total FROM phishing";
$total_urls_result = $conn->query($total_urls_query);
$total_urls = $total_urls_result->fetch_assoc()['total'];

// Fetch phishing URLs
$phishing_urls_query = "SELECT COUNT(*) as phishing FROM phishing WHERE if_phish=1";
$phishing_urls_result = $conn->query($phishing_urls_query);
$phishing_urls = $phishing_urls_result->fetch_assoc()['phishing'];

// Fetch legitimate URLs
$legitimate_urls = $total_urls - $phishing_urls; // Calculate legitimate URLs

// Return data as JSON
echo json_encode([
    'total_users' => $total_users,
    'active_users' => $active_users,
    'total_urls' => $total_urls,
    'phishing_urls' => $phishing_urls,
    'legitimate_urls' => $legitimate_urls, // Add legitimate URLs
]);

$conn->close();

