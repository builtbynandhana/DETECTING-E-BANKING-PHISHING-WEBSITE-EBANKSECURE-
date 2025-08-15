<?php
$servername = "localhost";
$username = "root";
$password = "pass123";
$db="phishing_detection";
// Create connection
$conn = new mysqli($servername, $username, $password,$db);

$message = ''; // Initialize an empty message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the URL from the form
    $url = $_POST['url'];

    // Validate URL format
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        // Prepare the SQL query to delete the URL from the table
        $sql = "DELETE FROM phishing WHERE url = ?";

        // Prepare the statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind the URL to the SQL statement
            $stmt->bind_param("s", $url);

            // Execute the statement
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $message = "URL deleted successfully!";
                } else {
                    $message = "URL not found in the database!";
                }
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
    <title>Delete URL</title>
    <link rel="stylesheet" href="admin_url.css"> <!-- Link to the CSS file -->
    <button class="back-btn" onclick="window.location.href='admin_dashboard.html'">← Back</button>
</head>
<body>
    <header>
        <h1>EbankSecure</h1>
        <p>Remove URL from Phishing Detection System</p>
    </header>

    <main>
        <h2>Delete a URL</h2>

        <div class="url-input">
            <form action="" method="POST">
                <label for="url">Enter URL to Delete:</label>
                <input type="url" id="url" name="url" placeholder="https://example.com" required>

                <button type="submit">Delete</button>
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
