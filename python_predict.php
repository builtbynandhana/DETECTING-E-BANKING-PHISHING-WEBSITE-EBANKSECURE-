<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $url = filter_var(trim($_POST["url"]), FILTER_SANITIZE_URL); // Get and sanitize the URL

    // Check if the URL is valid
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        // Call your Python script here and capture the output
        $output = shell_exec("python C:/wamp64/www/review/Geethu/python_final.py " . escapeshellarg($url));
        
        // Optionally decode JSON output
        $result = json_decode($output, true);
        
        // Prepare the message based on output
        if ($result['is_phishing'] == 1) {
            $message = "The URL '$url' is a phishing site!";
        } else {
            $message = "The URL '$url' is safe.";
        }
    } else {
        $message = "Invalid URL format.";
    }
} else {
    $message = "No URL submitted.";
}

// Redirect back to the original page with the message
header("Location: urlsearch.php?message=" . urlencode($message));
exit();
