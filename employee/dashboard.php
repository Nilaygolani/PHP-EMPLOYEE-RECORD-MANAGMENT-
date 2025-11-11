<?php
session_start();
include('../db_connect.php');
if (!isset($_SESSION['eid'])) {
    header("Location: index.php");
    exit();
}

$employee_id = $_SESSION['eid'];

$stmt_emp = $conn->prepare("SELECT * FROM employeedetail WHERE ID = ?");
$stmt_emp->bind_param("i", $employee_id);
$stmt_emp->execute();
$result_emp = $stmt_emp->get_result();
$employee = $result_emp->fetch_assoc();
$stmt_emp->close();


$stmt_tasks = $conn->prepare("SELECT * FROM tasks WHERE EmployeeID = ? ORDER BY DueDate ASC");
$stmt_tasks->bind_param("i", $employee_id);
$stmt_tasks->execute();
$tasks_result = $stmt_tasks->get_result();

$stmt_leaves = $conn->prepare("SELECT * FROM leaves WHERE EmployeeID = ? ORDER BY StartDate DESC");
$stmt_leaves->bind_param("i", $employee_id);
$stmt_leaves->execute();
$leaves_result = $stmt_leaves->get_result();
?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; 
            margin: 0; 
            background-color: #f4f7f6; 
            color: #333; 
        }
        .header { 
            background-color: #2c3e50; 
            color: white; 
            padding: 15px 30px; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header h1 { margin: 0; font-size: 24px; }
        .header nav a { 
            color: white; 
            text-decoration: none; 
            margin-left: 20px; 
            font-weight: bold; 
            transition: opacity 0.2s; 
        }
        .header nav a:hover { opacity: 0.8; }
        .container { 
            padding: 30px; 
            max-width: 1200px; 
            margin: 0 auto; 
        }
        .card { 
            background-color: white; 
            border-radius: 8px; 
            box-shadow: 0 4px 8px rgba(0,0,0,0.05); 
            margin-bottom: 30px; 
            padding: 25px; 
        }
        .card-header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            border-bottom: 1px solid #ecf0f1; 
            padding-bottom: 15px; 
            margin-bottom: 20px; 
        }
        .content-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
        }
        .content-table th, .content-table td { 
            border-bottom: 1px solid #ddd; 
            padding: 15px; 
            text-align: left; 
            vertical-align: middle;
        }
        .content-table th { 
            background-color: #f8f9fa; 
            color: #555; 
            font-weight: 600; 
        }
        .profile-section { 
            display: flex; 
            align-items: center; 
            gap: 30px; 
            flex-wrap: wrap; 
        }
        .profile-photo { 
            width: 140px; 
            height: 140px; 
            border-radius: 50%; 
            object-fit: cover; 
            border: 4px solid #ecf0f1; 
        }
        .profile-details p { 
            margin: 10px 0; 
            font-size: 16px; 
            color: #555; 
        }
        .profile-details strong { 
            display: inline-block; 
            width: 130px; 
            color: #333; 
        }

        /* */
        .status-select { 
            padding: 8px; 
            border-radius: 4px; 
            border: 1px solid #ddd; 
            font-family: inherit; 
            font-size: 14px; 
        }
        .update-btn { 
            background-color: #007bff; 
            color: white; 
            border: none; 
            padding: 8px 12px; 
            border-radius: 4px; 
            cursor: pointer; 
            transition: background-color 0.2s; 
        }
        .update-btn:hover { background-color: #0056b3; }
        .status-Completed { color: #28a745; font-weight: bold; }
        .status-InProgress { color: #fd7e14; font-weight: bold; }
        .status-Pending { color: #6c757d; font-weight: bold; }

        

.success-message {
    background-color: #d4edda;
    color: #155724;
    padding: 15px;
    border: 1px solid #c3e6cb;
    border-radius: 5px;
    margin: 20px 0;
    text-align: center;
}

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
        

<?php if(isset($_GET['status']) && $_GET['status'] == 'leave_applied'): ?>
    <div class="success-message">
        Your leave request has been submitted successfully!
    </div>
<?php endif; ?>
        <div class="card">
            <div class="card-header">
                <h2>👤My Profile</h2>
                <div class="actions">
                    <a href="edit-profile.php" title="Edit Profile">✏️</a>
                    <a href="changep.php" title="Change Password">🔑</a>
                </div>
            </div>
            <div class="profile-section">
                <div class="profile-photo-container">
                    <img src="profile_pics/<?php echo htmlspecialchars($employee['ProfilePhoto']); ?>" alt="Profile Photo" class="profile-photo" onerror="this.onerror=null; this.src='profile_pics/default_avatar.png';">
                </div>
                <div class="profile-details">
                    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($employee['EmpFirstName'] . ' ' . $employee['EmpLastName']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($employee['EmpEmail']); ?></p>
                    <p><strong>NTM Code:</strong> <?php echo htmlspecialchars($employee['NTMCode']); ?></p>
                    <p><strong>Joining Date:</strong> <?php echo date("d M, Y", strtotime($employee['JoiningDate'])); ?></p>
                    <p><strong>Designation:</strong> <?php echo htmlspecialchars($employee['EmpDesignation']); ?></p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2>📝My Tasks</h2>
            </div>
            <table class="content-table">
                <thead>
                    <tr>
                        <th>Task Title</th>
                        <th>Description</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Action</th> </tr>
                </thead>
                <tbody>
                    <?php if ($tasks_result->num_rows > 0): ?>
                        <?php while($task = $tasks_result->fetch_assoc()): ?>
                            <tr>
                                <form action="update_task_status.php" method="POST" style="display: contents;">
                                    <input type="hidden" name="task_id" value="<?php echo $task['ID']; ?>">
                                    
                                    <td><?php echo htmlspecialchars($task['TaskTitle']); ?></td>
                                    <td><?php echo htmlspecialchars($task['TaskDescription']); ?></td>
                                    <td><?php echo date("d M, Y", strtotime($task['DueDate'])); ?></td>
                                    <td>
                                        <select name="new_status" class="status-select">
                                            <option value="Pending" <?php if($task['Status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                            <option value="In Progress" <?php if($task['Status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                                            <option value="Completed" <?php if($task['Status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button type="submit" class="update-btn">Update</button>
                                    </td>
                                </form>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align:center;">No Task Available</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="card">
            <div class="card-header">
                <h2>🗓️My Leave History</h2>
                <div style="font-size: 16px;">
                    <strong>Total Allocated Leaves: </strong>
                    <?php echo isset($employee['TotalLeaves']) ? htmlspecialchars($employee['TotalLeaves']) : 'N/A'; ?>
                </div>
            </div>
            <table class="content-table">
                <thead>
                    <tr>
                        <th>Reason</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($leaves_result->num_rows > 0): ?>
                        <?php while($leave = $leaves_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($leave['LeaveReason']); ?></td>
                                <td><?php echo date("d M, Y", strtotime($leave['StartDate'])); ?></td>
                                <td><?php echo date("d M, Y", strtotime($leave['EndDate'])); ?></td>
                                <td class="status-<?php echo strtolower(htmlspecialchars($leave['Status'])); ?>"><?php echo htmlspecialchars($leave['Status']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align:center;">No Leave Application</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>