<?php
session_start();
$message = "Welcome back";


$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "hst";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = $_POST["username"]; 
    $password = $_POST["password"];
    
    $stmt = $conn->prepare("SELECT userid, password FROM users WHERE userid = ?");
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verify the password using password_verify since you stored hashed passwords
        if (password_verify($password, $user["password"])) {
            $_SESSION["user"] = $userid;
            // Redirect to home.php after successful login
            header("Location: home.php");
            exit();
        } else {
            $message = "Invalid password. Please try again.";
        }
    } else {
        $message = "User ID not found. Please try again.";
    }
    
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Tracker Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
            margin: 0;
        }
        
        .left-section {
            flex: 1;
            background: linear-gradient(to right, #1E90FF, #87CEFA);
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            padding: 20px;
        }
        
        .right-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            background: white;
        }
        
        .login-container {
            background: #F0FFFF;
            padding: 25px;
            border-radius: 25px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
        }
        
        h2 {
            color: #1E90FF;
        }
        
        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #1E90FF;
            border-radius: 45px;
            font-size: 16px;
        }
        
        .btn1 button {
            background: #1E90FF;
            color: white;
            padding: 12px;
            border: none;
            width: 50%;
            cursor: pointer;
            border-radius: 45px;
            font-size: 16px;
            margin: 13px;
        }
        .btn2 button {
            background:rgb(255, 30, 30);
            color: white;
            padding: 12px;
            border: none;
            width: 50%;
            cursor: pointer;
            border-radius: 45px;
            font-size: 16px;
        }
        
        .btn1 button:hover {
            background: #4682B4;
        }
        .btn2 button:hover {
            background:rgb(145, 8, 8);
        }
        
        .message {
            margin-top: 10px;
            color: red;
            font-size: 14px;
        }
        
        .quote {
            font-size: 24px;
            font-weight: bold;
            max-width: 500px;
        }
        
        .signup-link {
            margin-top: 15px;
            font-size: 14px;
        }
        
        .signup-link a {
            color: #1E90FF;
            text-decoration: none;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="left-section">
        <div class="quote">
            "A healthy outside starts from the inside. Take care of your body, it's the only place you have to live."
        </div>
    </div>
    
    <div class="right-section">
        <div class="login-container">
            <h2>Health Tracker Login</h2>
            <p class="message"><?php echo $message; ?></p>
            <form method="POST" action="">
                <input type="text" name="username" placeholder="User ID" required>
                <input type="password" name="password" placeholder="Password" required>
                <div class="btn">
                    <div class="btn1"><button type="submit">Login</button></div>
                    <div class="btn2"><button type="reset">Cancel</button></div>
                </div>
            </form>
            <div class="signup-link">
                Don't have an account? <a href="signup.php">Sign up here</a>
            </div>
            <div class="signup-link">
                signup as admin? <a href="adminsignup.php">Admin sign up</a>
            </div>
        </div>
    </div>
</body>
</html>