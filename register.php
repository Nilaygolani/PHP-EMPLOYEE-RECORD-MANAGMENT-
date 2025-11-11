<?php
include('db_connect.php');
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $ntmcode = $_POST['ntmcode'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    if ($password !== $confirm_password) {
        $error = 'Passwords do not match!';
    } else {
        $stmt_check = $conn->prepare("SELECT ID FROM employeedetail WHERE EmpEmail = ?");
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows > 0) {
            $error = 'An account with this email already exists.';
        } else {
            $joining_date = date('Y-m-d'); 
            $stmt_insert = $conn->prepare("INSERT INTO employeedetail (EmpFirstName, EmpLastName, EmpEmail, NTMCode, EmpPassword, JoiningDate) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_insert->bind_param("ssssss", $fname, $lname, $email, $ntmcode, $password, $joining_date);

            if ($stmt_insert->execute()) {
                $success = "Registration successful! You can now <a href='employee/'>login</a>.";
            } else {
                $error = "An error occurred. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px 0; }
        .container { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 400px; text-align: center; }
        h2 { margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; text-align: left; }
        label { font-weight: bold; margin-bottom: 5px; display: block; }
        input[type="text"], input[type="email"], input[type="password"] { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        input[type="submit"] { width: 100%; padding: 12px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-top: 10px;}
        .message { margin-top: 15px; font-weight: bold; }
        .success { color: green; }
        .error { color: red; }
        .login-link { margin-top: 20px; display: block; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Employee Registration</h2>

        <?php if (!empty($success)): ?>
            <p class="message success"><?php echo $success; ?></p>
        <?php else: ?>
            <?php if (!empty($error)) { echo "<p class='message error'>$error</p>"; } ?>
            <form method="post" action="">
                <div class="form-group">
                    <label for="fname">First Name:</label>
                    <input type="text" name="fname" required>
                </div>
                <div class="form-group">
                    <label for="lname">Last Name:</label>
                    <input type="text" name="lname" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="ntmcode">Employee Code (NTMCode):</label>
                    <input type="text" name="ntmcode" required>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password:</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <input type="submit" value="Register">
            </form>
        <?php endif; ?>
        
        <a href="employee/" class="login-link">Already have an account? Login Here</a>
    </div>
</body>
</html>