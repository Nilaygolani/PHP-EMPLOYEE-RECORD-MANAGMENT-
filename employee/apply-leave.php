<?php
session_start();
include('../db_connect.php');
if (!isset($_SESSION['eid'])) {
    header("Location: index.php");
    exit();
}

$employee_id = $_SESSION['eid'];
$success_message = '';
$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];
    $status = 'Pending';
    $current_date = date('Y-m-d'); 
    if ($start_date < $current_date) {
        $error_message = "Start date cannot be in the past. Please select a valid date.";
    }
    // 2. Check karein ki end date, start date se pehle na ho
    elseif ($end_date < $start_date) {
        $error_message = "End date cannot be before the start date. Please select a valid date range.";
    }
    elseif ($start_date == $end_date) {
        $error_message = "Start date and end date cannot be the same. Please select different dates.";
    }
    else {
        $stmt = $conn->prepare("INSERT INTO leaves (EmployeeID, StartDate, EndDate, LeaveReason, Status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $employee_id, $start_date, $end_date, $reason, $status);

        
       if ($stmt->execute()) {
            echo "<script>
            alert('Leave applied successfully!');
            window.location.href = 'dashboard.php';
          </script>";
    exit();
}
        else {
            $error_message = "Error: Could not submit leave request.";
        }
        $stmt->close();
    }
}
$stmt_emp = $conn->prepare("SELECT EmpFirstName FROM employeedetail WHERE ID = ?");
$stmt_emp->bind_param("i", $employee_id);
$stmt_emp->execute();
$result_emp = $stmt_emp->get_result();
$employee = $result_emp->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Leave</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f4f7f6; color: #333; }
        .header { background-color: #2c3e50; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .header nav a { color: white; text-decoration: none; margin-left: 20px; font-weight: bold; }
        .header nav a:hover { text-decoration: underline; }
        .container { padding: 30px; }
        .card { background-color: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 30px; padding: 20px; max-width: 600px; margin: auto; }
        .card h2 { margin-top: 0; border-bottom: 2px solid #ecf0f1; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; font-family: Arial, sans-serif; }
        .btn-submit { background-color: #28a745; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .btn-back { display: inline-block; margin-bottom: 20px; color: #3498db; text-decoration: none; }
        .error-msg { background-color: #f8d7da; color: #721c24; padding: 10px; border: 1px solid #f5c6cb; border-radius: 5px; margin-bottom: 20px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Welcome, <?php echo htmlspecialchars($employee['EmpFirstName']); ?></h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="apply-leave.php">Apply for Leave</a>
            <a href="logout.php">Logout</a>
        </nav>
    </div>

    <div class="container">
        <div class="card">
            <a href="dashboard.php" class="btn-back">&larr; Back to Dashboard</a>
            <h2>Apply for Leave</h2>
            
            <?php if(!empty($error_message)) { echo "<div class='error-msg'>".$error_message."</div>"; } ?>
            
            <form action="apply-leave.php" method="post">
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label for="reason">Reason for Leave</label>
                    <textarea id="reason" name="reason" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn-submit">Submit Request</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('start_date').addEventListener('change', function() {
            var startDate = this.value;
            document.getElementById('end_date').min = startDate;
        });
    </script>

</body>
</html>