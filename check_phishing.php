<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the URL from the POST request
    $data = json_decode(file_get_contents("php://input"));
    $url = $data->reported_url;

    // Escape the URL for the shell command
    $escaped_url = escapeshellarg($url);

    // Run the Python script
    $command = "C:\\Users\\Admin\\AppData\\Local\\Programs\\Python\\Python313\\python.exe C:\\wamp64\\www\\review\\Geethu\\python_final.py $escaped_url";
    $output = shell_exec($command);

    // Check the result and return response as JSON
    if (trim($output) === 'Phishing') {
        echo json_encode(['result' => 'Phishing']);
    } else {
        echo json_encode(['result' => 'Legitimate']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>
