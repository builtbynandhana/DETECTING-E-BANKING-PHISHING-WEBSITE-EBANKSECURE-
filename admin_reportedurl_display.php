<?php
$servername = "localhost";
$username = "root";
$password = "pass123";
$db = "phishing_detection";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch reported users from user_reported_urls table
$sql = "SELECT * FROM user_reported_urls";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reported Users</title>
    <link rel="stylesheet" href="admin_user_display.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <h1>EbankSecure</h1>
        <p>Reported Users</p>
    </header>

    <main>
        <h2>List of Reported Users</h2>
        
        <!-- Message Area -->
        <div id="message" style="color: green; font-weight: bold;"></div>

        <div id="user-list">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>URL</th>
                        <th>Reason</th>
                        <th>Date Reported</th>
                        <th>Action</th>
                        <th>Check URL</th>
                        <th>Delete</th> <!-- New column for delete option -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        // Output data for each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['user_name']}</td>
                                    <td>{$row['reported_url']}</td>
                                    <td>{$row['reason']}</td>
                                    <td>{$row['date_reported']}</td>
                                    <td>
                                        <button id='add-button-{$row['id']}' onclick=\"addUrl('{$row['reported_url']}', {$row['id']})\">Add</button>
                                    </td>
                                    <td>
                                        <button id='check-button-{$row['id']}' onclick=\"checkUrl('{$row['reported_url']}', {$row['id']})\">Check</button>
                                        <span id='result-{$row['id']}'></span>
                                    </td>
                                    <td>
                                        <button id='delete-button-{$row['id']}' onclick=\"deleteUrl({$row['id']})\">Delete</button>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No reported users found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <script>
            function addUrl(url, rowId) {
                if (confirm('Are you sure you want to add this URL to the URLs table?')) {
                    // Send a request to the PHP script to add the URL
                    fetch('add_reporturl.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ reported_url: url }),
                    })
                    .then(response => {
                        if (response.ok) {
                            return response.json();  // Parse the JSON response
                        } else {
                            throw new Error('Network response was not ok.');
                        }
                    })
                    .then(data => {
                        const messageDiv = document.getElementById('message'); // Get the message area
                        const addButton = document.getElementById('add-button-' + rowId); // Get the specific button

                        if (data.success) {
                            messageDiv.innerText = 'URL added successfully.';
                            messageDiv.style.color = 'green'; // Set success color
                            addButton.innerText = 'Added'; // Change button text to 'Added'
                            addButton.disabled = true; // Optionally disable the button
                        } else {
                            messageDiv.innerText = 'Error adding URL: ' + (data.error || 'Unknown error.');
                            messageDiv.style.color = 'red'; // Set error color
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            }

            function checkUrl(url, rowId) {
                if (confirm('Do you want to check this URL for phishing?')) {
                    // Send request to check URL using Python model
                    fetch('check_phishing.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ reported_url: url })
                    })
                    .then(response => {
                        if (response.ok) {
                            return response.json(); // Parse the JSON response
                        } else {
                            throw new Error('Network response was not ok.');
                        }
                    })
                    .then(data => {
                        const resultSpan = document.getElementById('result-' + rowId); // Get the result span
                        if (data.result) {
                            resultSpan.innerText = data.result; // Display the result (phishing or legitimate)
                            resultSpan.style.color = (data.result === 'Phishing') ? 'red' : 'green';
                        } else {
                            resultSpan.innerText = 'Error checking URL';
                            resultSpan.style.color = 'red';
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            }

            function deleteUrl(id) {
                if (confirm('Are you sure you want to delete this URL?')) {
                    // Send a request to delete the URL
                    fetch('delete_reporturl.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id: id }),
                    })
                    .then(response => {
                        if (response.ok) {
                            return response.json();  // Parse the JSON response
                        } else {
                            throw new Error('Network response was not ok.');
                        }
                    })
                    .then(data => {
                        const messageDiv = document.getElementById('message'); // Get the message area
                        const row = document.querySelector(`#delete-button-${id}`).closest('tr'); // Get the row

                        if (data.success) {
                            messageDiv.innerText = 'URL deleted successfully.';
                            messageDiv.style.color = 'green'; // Set success color
                            row.remove(); // Remove the row from the table
                        } else {
                            messageDiv.innerText = 'Error deleting URL: ' + (data.error || 'Unknown error.');
                            messageDiv.style.color = 'red'; // Set error color
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            }
        </script>
    </main>

    <footer>
        <p>&copy; 2024 EbankSecure. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
