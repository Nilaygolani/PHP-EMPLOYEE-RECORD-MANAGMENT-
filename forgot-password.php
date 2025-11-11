<?php
session_start();
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('db_connect.php');
    $email = $_POST['email'];
    $ntm_code = $_POST['ntm_code'];
    

    $stmt = $conn->prepare("SELECT ID FROM employeedetail WHERE EmpEmail = ? AND NTMCode = ?");
    $stmt->bind_param("ss", $email, $ntm_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['reset_eid'] = $row['ID'];
        header("Location: reset-password.php");
        exit();
    } else {
        $error_message = "Invalid Email or Employee Code. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 350px; text-align: center; }
        h2 { margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; text-align: left; }
        label { font-weight: bold; margin-bottom: 5px; display: block; }
        input[type="email"], input[type="text"] { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        input[type="submit"] { width: 100%; padding: 12px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-top: 10px;}
        .error { color: red; margin-top: 15px; font-weight: bold; }
        .home-link { margin-top: 20px; display: block; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Reset Employee Password</h2>
        <p>Please verify your email and employee code to proceed.</p>

        <?php if (!empty($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="ntm_code">Employee Code:</label>
                <input type="text" name="ntm_code" required>
            </div>
            <input type="submit" value="Verify & Proceed">
        </form>
        
        <a href="index.php" class="home-link">Back to Home</a>
    </div>
</body>
</html>