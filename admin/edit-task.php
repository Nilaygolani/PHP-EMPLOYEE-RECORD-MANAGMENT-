<?php
session_start();
include('../db_connect.php');

if (!isset($_SESSION['aid'])) {
    header("Location: index.php");
    exit();
}

$task_id = $_GET['id'];
$error_message = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST['employee_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE tasks SET EmployeeID=?, TaskTitle=?, TaskDescription=?, DueDate=?, Status=? WHERE ID=?");
    $stmt->bind_param("issssi", $employee_id, $title, $description, $due_date, $status, $task_id);

    if ($stmt->execute()) {
        header("Location: tasks.php?status=updated");
        exit();
    } else {
        $error_message = "Error: Could not update the task.";
    }
    $stmt->close();
}


$stmt = $conn->prepare("SELECT * FROM tasks WHERE ID = ?");
$stmt->bind_param("i", $task_id);
$stmt->execute();
$result = $stmt->get_result();
$task = $result->fetch_assoc();

if (!$task) {
    echo "Task not found.";
    exit();
}
$employees_result = mysqli_query($conn, "SELECT ID, EmpFirstName, EmpLastName FROM employeedetail");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f4f7f6; }
        .sidebar { width: 250px; background-color: #2c3e50; color: white; position: fixed; height: 100%; padding-top: 20px; }
        .sidebar h2 { text-align: center; }
        .sidebar ul { list-style-type: none; padding: 0; }
        .sidebar ul li { padding: 15px 20px; border-bottom: 1px solid #34495e; }
        .sidebar ul li a { color: white; text-decoration: none; }
        .sidebar ul li a:hover { color: #3498db; }
        .main-content { margin-left: 260px; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; background-color: white; padding: 10px 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header .logout a { background-color: #e74c3c; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; }
        .form-container { background-color: white; padding: 20px; border-radius: 5px; margin-top: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn-submit { background-color: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .btn-back { display: inline-block; margin-bottom: 20px; color: #3498db; text-decoration: none; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="employees.php">Manage Employees</a></li>
            <li><a href="tasks.php">Task Assign</a></li>
            <li><a href="leaves.php">Manage Leaves</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <span>Task Management</span>
            <div class="logout">
                <a href="logout.php">Logout</a>
            </div>
        </div>
        
        <div class="form-container">
            <a href="tasks.php" class="btn-back">&larr; Back to Task List</a>
            <h1>Edit Task</h1>
            
            <form action="edit-task.php?id=<?php echo $task_id; ?>" method="post">
                <div class="form-group">
                    <label>Assign To</label>
                    <select name="employee_id" required>
                        <?php
                        while($employee = mysqli_fetch_assoc($employees_result)) {
                            $selected = ($employee['ID'] == $task['EmployeeID']) ? 'selected' : '';
                            echo "<option value='" . $employee['ID'] . "' $selected>" . htmlspecialchars($employee['EmpFirstName'] . ' ' . $employee['EmpLastName']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Task Title</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($task['TaskTitle']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Task Description</label>
                    <textarea name="description" rows="4"><?php echo htmlspecialchars($task['TaskDescription']); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Due Date</label>
                    <input type="date" name="due_date" value="<?php echo htmlspecialchars($task['DueDate']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="Pending" <?php if($task['Status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                        <option value="In Progress" <?php if($task['Status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                        <option value="Completed" <?php if($task['Status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                    </select>
                </div>
                <button type="submit" class="btn-submit">Update Task</button>
            </form>
        </div>
    </div>

</body>
</html>