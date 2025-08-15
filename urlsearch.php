<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EbankSecure - Phishing Detection</title>
    <link rel="stylesheet" href="urlsearch.css">
</head>
<body>
    <header>
        <h1>EbankSecure</h1>
        <p>Combat phishing with EbankSecure</p>
        <div class="back-btn">
            <a href="userhome.html"><i class="fas fa-arrow-left"></i> return</a>
        </div>
    </header>

    <main>
        <section class="url-input">
            <h2>Enter the URL to Check for Phishing:</h2>
            <form id="phishing-form" action="" method="post">
                <label for="url">Website URL:</label>
                <input type="url" id="url" name="url" placeholder="Enter a URL" required>
                <button type="submit">Check URL</button>
                <button class="back-btn" type="button" onclick="window.location.href='userhome.html'">← Back</button>
            </form>
        </section>

        <section id="results">
            <!-- The result of the phishing detection will be displayed here -->
            <?php
            $servername = "localhost"; // Change if necessary
            $username = "root"; // Your database username
            $password = "pass123"; // Your database password
            $dbname = "phishing_detection"; // Your database name

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['url'])) {
                // Prepare and bind
                $url = $_POST['url'];
                $stmt = $conn->prepare("INSERT INTO url_check_history (url) VALUES (?)");
                $stmt->bind_param("s", $url);
                $stmt->execute();
                $stmt->close();

                // Escape the URL for the shell command
                $escaped_url = escapeshellarg($url);
                $command = "C:\\Users\\Admin\\AppData\\Local\\Programs\\Python\\Python313\\python.exe C:\\wamp64\\www\\review\\Geethu\\python_final.py $escaped_url";
                $output = shell_exec($command);

                // Display the result
                if ($output !== null) {
                    echo "<p>Result: " . htmlspecialchars($output, ENT_QUOTES, 'UTF-8') . "</p>";
                } else {
                    echo "<p>Error: No output from the Python script.</p>";
                }
            }
            ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 EbankSecure - Detect E-Banking Phishing Websites</p>
    </footer>

    <script src="urlsearch.js"></script>
</body>
</html>
