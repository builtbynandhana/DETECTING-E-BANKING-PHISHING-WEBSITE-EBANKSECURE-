<?php
    $servername = "localhost";
    $username = "root";
    $password = "pass123";
    $db="phishing_detection";
    
    // Create connection
    $conn = new mysqli($servername, $username, $password,$db);
       
       session_start();
       
       if ($_SERVER["REQUEST_METHOD"] == "POST") {
           $username = $_POST['username'];
           $password = $_POST['password'];
       
           $sql = "SELECT id, password, role FROM user WHERE username='$username'";
           $result = $conn->query($sql);
       
           if ($result->num_rows == 1) {
               $row = $result->fetch_assoc();
             if (password_verify($password, $row['password'])) {
                   $_SESSION['user_id'] = $row['id'];
                   $_SESSION['role'] = $row['role'];
                   if ($row['role']) {
                    header("Location:admin_dashboard.html");
                } else {
                    header("Location:userhome.html");
                }
               } else {
                 echo  "<p class='error'>Invalid password!</p>";
               }
           } else {
             echo "<p class='error'>No user found with that username!</p>";
           }
       }
       ?>
       <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detect E-banking Phishing Website</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image:url(bgimg.jpg) ;
            background-color: #dde2e6;
            background-repeat: no-repeat;
            background-size: cover;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction:column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: #f6f6f7;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }
        h2 {
            color: #10659e;
        }
        input[type="text"],
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            background-color: #d3dbe2c0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button[type="submit"] {
    background: linear-gradient(45deg, #10659e, #104d86);
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 50px;
    cursor: pointer;
    font-size: 16px;
    transition: background 0.3s ease;
}

button[type="submit"]:hover {
    background: linear-gradient(45deg, #0c2e77, #0a204a);
}

    </style>
    <script src="login.js"></script>
</head>
<body>
    <div class="login-container">
        <h2>Detect E-banking Phishing Website</h2>
        <form action="" method="post">
            <input type="text"  name="username" placeholder="Username" required><br>
            <input type="password"  name="password" placeholder="Password" required><br>
            <button type="submit"> Login </button>
            <h4>New User?<a href="signup.html">Sign Up</a></h4>
        </form>
    </div>
</body>
</html>