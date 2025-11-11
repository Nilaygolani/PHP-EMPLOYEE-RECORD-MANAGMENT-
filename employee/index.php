<?php
session_start();
include('../db_connect.php');
$error_message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
   
    $stmt = $conn->prepare("SELECT ID, EmpPassword FROM employeedetail WHERE EmpEmail = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        if ($password == $row['EmpPassword']) {
            $_SESSION['eid'] = $row['ID']; 
            header("Location: dashboard.php");
            exit();
        } else {
          
            $error_message = "Invalid Password!";
        }
    } else {
        $error_message = "Invalid Username!";
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
    <title>Employee Login</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-container { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 300px; text-align: center; }
        h2 { margin-bottom: 20px; }
        input[type="email"], input[type="password"] { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        input[type="submit"] { width: 100%; padding: 12px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .error { color: red; margin-top: 15px; }
        .links { margin-top: 20px; font-size: 14px; }
        .links a { margin: 0 10px; color: #007bff; text-decoration: none; }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>Employee Login</h2>
        <form method="post" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
            <?php if(!empty($error_message)) { echo "<p class='error'>".$error_message."</p>"; } ?>
        </form>
        <div class="links">
            <a href="../index.php">Home</a><a href="../forgot-password.php">Forgot Password?</a> | <a href="../register.php">Register</a>
        </div>
    </div>

</body>
</html>