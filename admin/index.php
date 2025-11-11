<?php
session_start();
include('../db_connect.php');
$error_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adminuser = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT ID, Password FROM tbladmin WHERE AdminName = ?");
    $stmt->bind_param("s", $adminuser);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if ($password == $row['Password']) {
            $_SESSION['aid'] = $row['ID'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error_message = "wrong password";
        }
    } else {
        $error_message = "wrong username";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
   <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 300px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .error {
            color: red;
            margin-top: 15px;
        }
        .home-link {
            margin-top: 20px;
            display: block;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form method="post" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="login" value="Login">
            <?php if(!empty($error_message)) { echo "<p class='error'>".$error_message."</p>"; } ?>
        </form>
        <a href="../index.php" class="home-link">Back to Home</a>
    </div>
</body>
</html>