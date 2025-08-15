<?php
$servername = "localhost";
$username = "root";
$password = "pass123";
$dbname = "phishing_detection";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$results_per_page = 50;
$page = 1; // Default to page 1
$search_result = [];
$search_message = "";
$search_url = "";

// Determine which page number visitor is currently on
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = (int)$_GET['page'];
}

// Determine the SQL LIMIT starting number for the results on the displaying page
$start_from = ($page - 1) * $results_per_page;

// Check if a search has been made
if (isset($_POST['search'])) {
    $search_url = $conn->real_escape_string($_POST['search_url']);
    $search_query = "SELECT id, url, if_phish FROM urlsnew WHERE url LIKE '%$search_url%'";
    $search_result = $conn->query($search_query);
    
    // Prepare message based on results
    if ($search_result->num_rows > 0) {
        $search_message = "Found the following URLs matching: " . htmlspecialchars($search_url);
    } else {
        $search_message = "No URLs found matching: " . htmlspecialchars($search_url);
    }
} else {
    // Fetch all URLs if no search is performed
    $search_result = $conn->query("SELECT id, url, if_phish FROM urlsnew LIMIT $start_from, $results_per_page");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of URLs</title>
    <link rel="stylesheet" href="display_urls.css">
    <button class="back-btn" onclick="window.location.href='userhome.html'">← Back</button>
</head>
<body>

<h2>List of URLs</h2>

<!-- Search Form -->
<form method="POST" action="">
    <input type="text" name="search_url" placeholder="Search URL..." value="<?php echo htmlspecialchars($search_url); ?>" required>
    <button type="submit" name="search">Search</button>
</form>

<!-- Search Result Message -->
<?php if (!empty($search_message)): ?>
    <p style="font-weight: bold;"><?php echo $search_message; ?></p>
<?php endif; ?>

<!-- Display Table -->
<?php
// Check if any rows were returned for the search query
if ($search_result && $search_result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>URL</th><th>Phishing Status</th></tr>";

    // Output each row of data
    while ($row = $search_result->fetch_assoc()) {
        echo "<tr style='background-color: #e0f7fa;'>"; // Highlight color for found URLs
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . htmlspecialchars($row['url']) . "</td>";
        echo "<td>" . ($row['if_phish'] ? "Phishing" : "Legitimate") . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    // Display message if no URLs are found
    echo "<p>No URLs found.</p>";
}
?>

</body>
</html>
