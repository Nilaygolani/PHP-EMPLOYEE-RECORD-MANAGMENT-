<?php
session_start();
include('../db_connect.php');

// Security Check: If employee is not logged in, redirect
if (!isset($_SESSION['eid'])) {
    header("Location: index.php");
    exit();
}

$employee_id = $_SESSION['eid'];
$error = '';
$success = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    // 1. Fetch current password from DB
    $stmt = $conn->prepare("SELECT EmpPassword FROM employeedetail WHERE ID = ?");
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();
    $db_password = $employee['EmpPassword'];

    // 2. Verify current password
    if ($current_password !== $db_password) {
        $error = "Your current password is incorrect.";
    } elseif ($new_password !== $confirm_new_password) {
        // 3. Check if new passwords match
        $error = "New passwords do not match.";
    } else {
        // 4. All checks passed, update the password
        $stmt_update = $conn->prepare("UPDATE employeedetail SET EmpPassword = ? WHERE ID = ?");
        $stmt_update->bind_param("si", $new_password, $employee_id);
        
        if ($stmt_update->execute()) {
            $success = "Password changed successfully!";
        } else {
            $error = "An error occurred. Please try again.";
        }
    }
}

// Fetch employee name for the header
$stmt_emp = $conn->prepare("SELECT EmpFirstName FROM employeedetail WHERE ID = ?");
$stmt_emp->bind_param("i", $employee_id);
$stmt_emp->execute();
$result_emp = $stmt_emp->get_result();
$employee_header = $result_emp->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
    
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f4f7f6; color: #333; }
        .header { background-color: #2c3e50; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .header nav a { color: white; text-decoration: none; margin-left: 20px; font-weight: bold; }
        .container { padding: 30px; }
        .card { background-color: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); max-width: 500px; margin: auto; padding: 20px; }
        .card h2 { margin-top: 0; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-submit { background-color: #007bff; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .message { margin-top: 15px; font-weight: bold; padding: 10px; border-radius: 5px; }
        .success { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb;}
        .error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb;}
    </style>
</head>
<body>

    <div class="header">
        <h1>Welcome, <?php echo htmlspecialchars($employee_header['EmpFirstName']); ?></h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="apply-leave.php">Apply for Leave</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>

    <div class="container">
        <div class="card">
            <h2>🔒Change Your Password</h2>
            
            <?php if (!empty($success)) { echo "<p class='message success'>$success</p>"; } ?>
            <?php if (!empty($error)) { echo "<p class='message error'>$error</p>"; } ?>

            <form action="changep.php" method="post">
                <div class="form-group">
                    <label for="current_password">Current Password:</label>
                    <input type="password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_new_password">Confirm New Password:</label>
                    <input type="password" name="confirm_new_password" required>
                </div>
                <button type="submit" class="btn-submit">Change Password</button>
            </form>
        </div>
    </div>

</body>
</html>