<?php
$servername = "localhost";
$username = "root";
$password = "pass123";
$db="phishing_detection";
// Create connection
$conn = new mysqli($servername, $username, $password,$db);

$message = ''; // Initialize an empty message variable

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the old URL and new URL from the form
    $old_url = $_POST['old_url'];
    $new_url = $_POST['new_url'];

    // Validate URL format
    if (filter_var($old_url, FILTER_VALIDATE_URL) && filter_var($new_url, FILTER_VALIDATE_URL)) {
        // Prepare the SQL query to update the URL in the table
        $sql = "UPDATE phishing SET url = ? WHERE url = ?";

        // Prepare the statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind the new URL and old URL to the SQL statement
            $stmt->bind_param("ss", $new_url, $old_url);

            // Execute the statement
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $message = "URL updated successfully!";
                } else {
                    $message = "Old URL not found in the database!";
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
    <title>Update URL</title>
    <link rel="stylesheet" href="admin_url.css"> <!-- Link to the CSS file -->
    <button class="back-btn" onclick="window.location.href='admin_dashboard.html'">← Back</button>
</head>
<body>
    <header>
        <h1>EbankSecure</h1>
        <p>Update URL in Phishing Detection System</p>
    </header>

    <main>
        <h2>Update a URL</h2>

        <div class="url-input">
            <form action="" method="POST">
                <label for="old_url">Enter Old URL:</label>
                <input type="url" id="old_url" name="old_url" placeholder="https://old-example.com" required>

                <label for="new_url">Enter New URL:</label>
                <input type="url" id="new_url" name="new_url" placeholder="https://new-example.com" required>

                <button type="submit">Update</button>
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
