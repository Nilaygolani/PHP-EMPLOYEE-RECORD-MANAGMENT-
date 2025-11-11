<?php
session_start();
include('../db_connect.php');
if (!isset($_SESSION['aid'])) {
    header("Location: index.php");
    exit();
}
$query = "SELECT tasks.*, employeedetail.EmpFirstName, employeedetail.EmpLastName 
          FROM tasks 
          JOIN employeedetail ON tasks.EmployeeID = employeedetail.ID 
          ORDER BY tasks.DueDate ASC";
$result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tasks</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f4f7f6; }
        .sidebar { width: 250px; background-color: #2c3e50; color: white; position: fixed; height: 100%; padding-top: 20px; }
        .sidebar h2 { text-align: center; color: #ecf0f1; }
        .sidebar ul { list-style-type: none; padding: 0; }
        .sidebar ul li { padding: 15px 20px; border-bottom: 1px solid #34495e; }
        .sidebar ul li a { color: white; text-decoration: none; }
        .sidebar ul li a:hover { color: #3498db; }
        .main-content { margin-left: 260px; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; background-color: white; padding: 10px 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header .logout a { background-color: #e74c3c; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; }
        
        .content-table { width: 100%; margin-top: 20px; border-collapse: collapse; background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .content-table th, .content-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .content-table th { background-color: #f2f2f2; }
        .add-btn { background-color: #28a745; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>🤵Admin Panel</h2>
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
        
        <div style="margin-top: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h1>📝Task List</h1>
                <a href="add-task.php" class="add-btn">+ Add New Task</a>
            </div>

            <table class="content-table">
                <thead>
                    <tr>
                        <th>Task Title</th>
                        <th>Assigned To</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['TaskTitle']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['EmpFirstName'] . ' ' . $row['EmpLastName']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['DueDate']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Status']) . "</td>";
                            echo "<td><a href='edit-task.php?id=" . $row['ID'] . "'>Edit</a> | <a href='delete-task.php?id=" . $row['ID'] . "'>Delete</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center;'>No tasks found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>