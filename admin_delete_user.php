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

// Fetch users with role = 0 (non-admins) from the database
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

// Check if a user is to be deleted
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Prepare the SQL query to delete the user from the table
    $sql = "DELETE FROM user WHERE id = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the user ID to the SQL statement
        $stmt->bind_param("i", $delete_id);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to the same page to avoid re-submission
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "Error deleting user: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }
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
    <link rel="stylesheet" href="admin_delete_user.css"> <!-- Link to the CSS file -->
    <button class="back-btn" onclick="window.location.href='admin_dashboard.html'">← Back</button>
    <script>
        function confirmDelete(userId) {
            if (confirm("Are you sure you want to delete this user?")) {
                window.location.href = "?delete_id=" + userId; // Redirect to delete the user
            }
        }
    </script>
</head>
<body>
    <header>
        <h1>EbankSecure</h1>
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <button onclick="confirmDelete(<?php echo $user['id']; ?>)">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No users found.</p>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 EbankSecure. All rights reserved.</p>
    </footer>
</body>
</html>
