<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "pass123";
$db = "phishing_detection";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Redirect to login page if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Change this to your login page location
    exit();
}

// Handle deletion of feedback
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM feedbacks WHERE id='$delete_id'";
    
    if ($conn->query($delete_sql) === TRUE) {
        echo "<p class='success-message'>Feedback deleted successfully!</p>";
    } else {
        echo "Error deleting feedback: " . $conn->error;
    }
}
if (isset($_GET['reply'])) {
    $reply = intval($_GET['reply']);
    $reply = "INSERT INTO feedbacks WHERE id='id'";
    
    if ($conn->query($reply) === TRUE) {
        echo "<p class='success-message'>relied!</p>";
    } else {
        echo "Error : " . $conn->error;
    }
}

// Fetch feedback data
$sql = "SELECT f.id, u.username, f.feedback, f.rating FROM feedbacks f JOIN user u ON f.user_id = u.id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Management</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background-color: #0d223d;
            color: #fff;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 36px;
            margin: 0;
        }

        header p {
            font-size: 18px;
            margin: 10px 0 0;
        }

        main {
            padding: 40px 0;
            text-align: center;
        }

        h2 {
            color: #000;
            font-size: 28px;
            margin-bottom: 20px;
        }

        /* User List Section */
        #user-list {
            margin: 0 auto;
            width: 80%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #0d223d;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .delete-button {
            color: red;
            cursor: pointer;
            text-decoration: underline;
        }
        .reply-button {
            color: red;
            cursor: pointer;
            text-decoration: underline;
        }


        .success-message {
            color: green;
            margin: 15px 0;
        }
    </style>
</head>
<body>

<header>
    <h1>Feedback Management</h1>
    <p>Manage user feedback submissions.</p>
</header>

<main>
    <div id="user-list">
        <?php
        if ($result->num_rows > 0) {
            echo "<h2>Feedback List</h2>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Username</th><th>Feedback</th><th>Rating</th><th>Action</th><th>Reply</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td>" . $row['feedback'] . "</td>";
                echo "<td>" . str_repeat("&#9733;", $row['rating']) . str_repeat("&#9734;", 5 - $row['rating']) . "</td>";
                echo "<td><span class='delete-button' onclick='confirmDelete(" . $row['id'] . ")'>Delete</span></td>";
                echo "<td><span class='reply-button'onclick='confirmReply(" . $row['id'] . ")'reply</span></td>";
            
                
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No feedback available.</p>";
        }
        ?>
    </div>
</main>

<script>
    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this feedback?")) {
            window.location.href = "admin_feedback.php?delete_id=" + id;  // Redirect to the same page with delete_id
        }
    }
    function confirmReply()
    {
        if(confirm("are you sure you want to reply "))
    {
        window.location.href="admin_feedback.php?reply=" +id;
    }
    }
</script>
 
</body>
</html>
