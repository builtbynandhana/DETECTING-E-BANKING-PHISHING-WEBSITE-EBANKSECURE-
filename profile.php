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

// Check if the form was submitted
$feedbackSubmitted = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $feedback = $conn->real_escape_string($_POST['feedback']);
    $rating = intval($_POST['rating']);

    // Insert the feedback and rating into the feedback table
    $sql = "INSERT INTO feedbacks (user_id, feedback, rating) VALUES ('$user_id', '$feedback', '$rating')";
    
    if ($conn->query($sql) === TRUE) {
        $feedbackSubmitted = true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch profile information
$user_id = $_SESSION['user_id'];
$sql = "SELECT username, email, created_at FROM user WHERE id = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Fetch feedback and replies for this user
$feedback_sql = "SELECT id, feedback, rating, reply FROM feedbacks WHERE user_id = '$user_id'";
$feedback_result = $conn->query($feedback_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #dde2e6;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .profile-container {
            background-color: #f6f6f7;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
        }
        h2 {
            color: #10659e;
        }
        .profile-info p {
            margin: 10px 0;
        }
        .feedback-section {
            margin-top: 30px;
        }
        textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
        }
        .rating-stars {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }
        .star {
            font-size: 2rem;
            cursor: pointer;
            color: #ccc;
            transition: color 0.3s;
        }
        .star.selected {
            color: #f39c12;
        }
        button[type="submit"] {
            background: linear-gradient(45deg, #10659e, #104d86);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s ease;
        }
        button[type="submit"]:hover {
            background: linear-gradient(45deg, #0c2e77, #0a204a);
        }
        .success-message {
            color: green;
            margin-top: 15px;
        }
        .feedback-reply {
            background-color: #e1f7d5;
            padding: 10px;
            margin-top: 20px;
            border-radius: 5px;
            font-style: italic;
        }
    </style>
</head>
<body>

    <!-- Profile Container -->
    <div class="profile-container">
        <h2>User Profile</h2>
        <div class="profile-info">
            <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
            <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
            <p><strong>Joined:</strong> <?php echo $user['created_at']; ?></p>
        </div>

        <!-- Feedback Section -->
        <div class="feedback-section">
            <h3>Submit Feedback</h3>
            <form action="" method="post">
                <textarea name="feedback" placeholder="Write your feedback here..." required></textarea>

                <div class="rating-stars">
                    <span class="star">&#9733;</span>
                    <span class="star">&#9733;</span>
                    <span class="star">&#9733;</span>
                    <span class="star">&#9733;</span>
                    <span class="star">&#9733;</span>
                </div>
                <input type="hidden" name="rating" id="rating-input" value="0">

                <button type="submit">Submit Feedback</button>
            </form>
            <?php if ($feedbackSubmitted): ?>
                <p class="success-message">Feedback submitted successfully!</p>
            <?php endif; ?>
        </div>

        <!-- Display User's Feedback and Replies -->
        <div class="feedback-list">
            <h3>Your Feedback</h3>
            <?php if ($feedback_result->num_rows > 0): ?>
                <?php while($feedback = $feedback_result->fetch_assoc()): ?>
                    <div class="feedback-item">
                        <p><strong>Feedback:</strong> <?php echo $feedback['feedback']; ?></p>
                        <p><strong>Rating:</strong> <?php echo $feedback['rating']; ?> Stars</p>
                        <?php if ($feedback['reply']): ?>
                            <p class="feedback-reply"><strong>Admin Reply:</strong> <?php echo $feedback['reply']; ?></p>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No feedback submitted yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- JavaScript for Star Rating and Textarea Validation -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const stars = document.querySelectorAll(".star");
            const feedbackForm = document.querySelector("form");
            const feedbackTextarea = document.querySelector("textarea");
            let selectedRating = 0;

            stars.forEach((star, index) => {
                star.addEventListener("click", () => {
                    selectedRating = index + 1;
                    document.getElementById("rating-input").value = selectedRating;
                    updateStars(selectedRating);
                });

                star.addEventListener("mouseover", () => {
                    updateStars(index + 1);
                });

                star.addEventListener("mouseout", () => {
                    updateStars(selectedRating);
                });
            });

            function updateStars(rating) {
                stars.forEach((star, i) => {
                    if (i < rating) {
                        star.classList.add("selected");
                    } else {
                        star.classList.remove("selected");
                    }
                });
            }

            // Form validation to ensure textarea is not empty
            feedbackForm.addEventListener("submit", (event) => {
                if (feedbackTextarea.value.trim() === "") {
                    alert("Please provide your feedback before submitting.");
                    event.preventDefault();  // Prevent form submission
                } else if (selectedRating === 0) {
                    alert("Please provide a rating before submitting.");
                    event.preventDefault();  // Prevent form submission
                }
            });
        });
    </script>

</body>
</html>
