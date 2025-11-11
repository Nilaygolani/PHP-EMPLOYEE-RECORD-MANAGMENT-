<?php
session_start();
include('../db_connect.php');


if (!isset($_SESSION['aid'])) {
    header("Location: index.php");
    exit();
}


$admin_id = $_SESSION['aid'];
$result = mysqli_query($conn, "SELECT AdminName FROM tbladmin WHERE ID = '$admin_id'");
$row = mysqli_fetch_assoc($result);
$admin_name = $row['AdminName'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
        }
        .container {
            display: flex;
            min-height: 100vh;
        }
        
        
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }
        .sidebar li {
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .sidebar li a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 15px 20px;
            transition: background-color 0.3s;
        }
        .sidebar li a:hover {
            background-color: #34495e;
        }
        .sidebar li.active {
            background-color: #34495e;
        }
        
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            background-color: white;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: white;
            padding: 15px 30px;
            border-bottom: 1px solid #ddd;
        }
        .welcome-text {
            font-weight: bold;
            color: #333;
        }
        .logout-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none; 
            display: inline-block;
        }
        .logout-btn:hover {
            background-color: #c0392b;
        }
        .dashboard-body {
            padding: 20px;
        }
        .dashboard-body h1 {
            font-size: 2.5em;
            color: #333;
            margin-bottom: 10px;
        }
        .dashboard-body p {
            color: #777;
            line-height: 1;
            margin-bottom: 0px;
        }
        .image-container {
            text-align: center;
        }
        .image-container img {
            width: 800px;
            height: 100;
            justify-self: center;
        }
    </style>
</head>
<body>

    <div class="container">
        
        <div class="sidebar">
            <h2>🤵Admin Panel</h2>
            <ul>
                <li class="active"><a href="dashboard.php">Dashboard</a></li>
                <li><a href="employees.php">Manage Employees</a></li>
                <li><a href="tasks.php">Task Assign</a></li>
                <li><a href="leaves.php">Manage Leaves</a></li>
            </ul>
        </div>

        <div class="main-content">
            
            <div class="header">
                <span>Welcome, <strong><?php echo htmlspecialchars($admin_name); ?></strong></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>

            <div class="dashboard-body">
                <h1>Dashboard</h1>
                <p>Welcome to the admin dashboard. Please use the navigation on the left to manage the system.</p>
                
                <div class="image-container">
                    <img src="01.png" alt="ADMIN blocks">
                </div>
            </div>
            
        </div>
    </div>

</body>
</html>