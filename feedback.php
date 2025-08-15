<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "pass123";
$dbname = "phishing_detection";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reply'])) {
    $feedback_id = $_POST['feedback_id'];
    $reply = $_POST['reply'];

    $query = "UPDATE feedbacks SET reply = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $reply, $feedback_id);

    if ($stmt->execute()) {
        $message = "Reply added successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Handle delete feedback
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_feedback'])) {
    $feedback_id = $_POST['feedback_id'];

    $query = "DELETE FROM feedbacks WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $feedback_id);

    if ($stmt->execute()) {
        $message = "Feedback deleted successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all feedbacks
$query = "SELECT * FROM feedbacks";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Feedback Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }
        h2 {
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        table th {
            background-color: #051831;
            color: #fff;
        }
        form button {
            background-color: #051831;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }
        form button:hover {
            background-color: #051831;
        }
    </style>
</head>
<body>
    <h2>Admin Feedback Management</h2>
    <?php if (!empty($message)) echo "<p style='color: green;'>$message</p>"; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Feedback</th>
            <th>Rating</th>
            <th>Submitted At</th>
            <th>Reply</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['user_id']; ?></td>
            <td><?php echo $row['feedback']; ?></td>
            <td><?php echo $row['rating']; ?></td>
            <td><?php echo $row['submitted_at']; ?></td>
            <td><?php echo $row['reply'] ? $row['reply'] : 'No reply yet'; ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="feedback_id" value="<?php echo $row['id']; ?>">
                    <input type="text" name="reply" placeholder="Reply here" required>
                    <button type="submit" name="submit_reply">Reply</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="feedback_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="delete_feedback">Delete</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
