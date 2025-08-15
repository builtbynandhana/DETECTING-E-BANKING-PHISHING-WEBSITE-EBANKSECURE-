<?php

$servername = "localhost";
$username = "root";
$password = "pass123";
$db="phishing_detection";
// Create connection
$conn = new mysqli($servername, $username, $password,$db);

$message = ''; // Initialize an empty message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the URL and if_phish from the form
    $url = $_POST['url'];
    $if_phish = $_POST['if_phish'];

    // Validate URL format
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        // Prepare the SQL query to insert the URL into the table
        $sql = "INSERT INTO phishing (url, if_phish) VALUES (?, ?)";

        // Prepare the statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind the URL and if_phish to the SQL statement
            $stmt->bind_param("si", $url, $if_phish);

            // Execute the statement
            if ($stmt->execute()) {
                $message = "URL inserted successfully!";
            } else {
                $message = "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            $message = "Error preparing the statement: " . $conn->error;
        }
    } else {
        $message = "Invalid URL format!";
    }

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit URL</title>
    <link rel="stylesheet" href="admin_url.css"> <!-- Link to the CSS file -->
    <button class="back-btn" onclick="window.location.href='admin_dashboard.html'">← Back</button>
</head>
<body>
    <header>
        <h1>EbankSecure</h1>
        <p>Combat Phishing with Secure URL Submission</p>
    </header>

    <main>
        <h2>Submit a URL</h2>

        <div class="url-input">
            <form action="" method="POST">
                <label for="url">Enter URL:</label>
                <input type="url" id="url" name="url" placeholder="https://example.com" required>

                <div class="radio-group">
                    <label>
                        <input type="radio" name="if_phish" value="0" required> Legitimate
                    </label>
                    <label>
                        <input type="radio" name="if_phish" value="1" required> Phishing
                    </label>
                </div>

                <button type="submit">Submit</button>
            </form>
        </div>

        <div id="results">
            <?php if ($message): ?>
                <p><?php echo $message; ?></p> <!-- Display the result message here -->
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 EbankSecure. All rights reserved.</p>
    </footer>
</body>
</html>
