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

// Fetch users with role = 0 from the database
$users = [];
$sql = "SELECT id, username, email FROM user WHERE role = 0"; // Only fetch users with role = 0

if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row; // Store users in an array
    }
    $result->free();
} else {
    echo "Error fetching users: " . $conn->error;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Users</title>
    <link rel="stylesheet" href="admin_user_display.css"> <!-- Link to the CSS file -->
    <button class="back-btn" onclick="window.location.href='admin_dashboard.html'">← Back</button>
</head>
<body>
    <header>
        
        <p>List of Users</p>
    </header>

    <main>
        <h2>User List</h2>

        <div id="user-list" class="url-input">
            <?php if (!empty($users)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No users found with role = 0.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 EbankSecure. All rights reserved.</p>
    </footer>
</body>
</html>
