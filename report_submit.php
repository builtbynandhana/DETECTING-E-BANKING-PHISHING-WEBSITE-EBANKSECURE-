<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
$servername = "localhost";
$username = "root";
$password = "pass123";
$db="phishing_detection";
// Create connection
$conn = new mysqli($servername, $username, $password,$db);
// Adjust the path if necessary
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['name'];
    $reported_url = $_POST['url'];
    $reason = $_POST['reason'];

    // Check if the connection is successful
    if ($conn) {
        // Prepare the statement
        $stmt = $conn->prepare("INSERT INTO user_reported_urls (user_name, reported_url, reason) VALUES (?, ?, ?)");
        
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param("sss", $user_name, $reported_url, $reason);

        // Execute the statement
        if ($stmt->execute()) {
            $message= "Report submitted successfully!";
        } else {
            $message= "Error submitting report: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        $message="Database connection failed.";
    }

    // Close the connection
    $conn->close();
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report a URL</title>
    <link rel="stylesheet" href="report.css"> <!-- Link to your main CSS -->
</head>
<body>

    <header>
        <nav>
            <h1>EbankSecure</h1>
            <ul>
                <li><a href="userhome.html">Home</a></li>
                <li><a href="profile.php">Profile</a></li>
                
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Report a Suspicious URL</h2>

        <form id="reportForm" action="" method="post">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" required>

            <label for="url">URL to Report</label>
            <input type="url" id="url" name="url" required>

            <label for="reason">Reason (Optional)</label>
            <textarea id="reason" name="reason" placeholder="Explain why you're reporting this URL (optional)"></textarea>

            <button type="submit">Submit Report</button>
             <label for="reply">reply</label>
            <textarea id="reply" name="reply" placeholder=""></textarea>
        </form>
        <section id="results">
         <!-- Display the message below the form -->
         <?php if (!empty($message)): ?>
            <div class="message">
                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif;
         ?>

        <a href="userhome.html" class="back-link">back</a>
    </div>

    </section>

</body>
</html>



