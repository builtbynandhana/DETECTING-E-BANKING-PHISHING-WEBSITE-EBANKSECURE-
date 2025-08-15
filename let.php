<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $url = $_POST['url'];  // Get the URL from the form

    // Specify the path to the Python executable and the script
    $pythonPath = "C:\\Users\\Admin\\AppData\\Local\\Programs\\Python\\Python312\\python.exe"; // Path to python.exe
    $scriptPath = "C:\\wamp64\\www\\review\\Geethu\\python_final.py"; // Path to your Python script

    // Call the Python script and pass the URL as an argument
    $output = shell_exec("$pythonPath $scriptPath " . escapeshellarg($url) . " 2>&1");

    // Process the output
    $outputMessage = !empty($output) ? htmlspecialchars($output, ENT_QUOTES, 'UTF-8') : 'No output from script';
    echo "<div id='results'><p>Result: " . $outputMessage . "</p></div>";
}

