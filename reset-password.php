<?php
session_start();

if (!isset($_SESSION['reset_eid'])) {
    header("Location: forgot-password.php");
    exit();
}

$message = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('db_connect.php');
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    $employee_id = $_SESSION['reset_eid'];

    if ($new_password !== $confirm_password) {
        $error = "Passwords do not match. Please try again.";
    } else {
        $stmt = $conn->prepare("UPDATE employeedetail SET EmpPassword = ? WHERE ID = ?");
        $stmt->bind_param("si", $new_password, $employee_id);
        
        if ($stmt->execute()) {
            unset($_SESSION['reset_eid']);
            $message = "Your password has been reset successfully! You can now <a href='employee/'>login</a>.";
        } else {
            $error = "An error occurred. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 350px; text-align: center; }
        h2 { margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; text-align: left; }
        label { font-weight: bold; margin-bottom: 5px; display: block; }
        input[type="password"] { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        input[type="submit"] { width: 100%; padding: 12px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-top: 10px;}
        .message { color: green; margin-top: 15px; font-weight: bold; }
        .error { color: red; margin-top: 15px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Set a New Password</h2>

        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php else: ?>
            <p>Please enter and confirm your new password.</p>
            <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>
            <form method="post" action="">
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <input type="submit" value="Reset Password">
            </form>
        <?php endif; ?>
    </div>
</body>
</html>