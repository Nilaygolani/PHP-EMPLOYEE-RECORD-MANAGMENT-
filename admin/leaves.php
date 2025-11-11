<?php
session_start();
include('../db_connect.php');
if (!isset($_SESSION['aid'])) {
    header("Location: index.php");
    exit();
}
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $leave_id = $_GET['id'];
    $new_status = '';

    if ($action == 'approve') {
        $new_status = 'Approved';
    } elseif ($action == 'reject') {
        $new_status = 'Rejected';
    }

    if (!empty($new_status)) {
        $stmt = $conn->prepare("UPDATE leaves SET Status = ? WHERE ID = ?");
        $stmt->bind_param("si", $new_status, $leave_id);
        $stmt->execute();
        header("Location: leaves.php");
        exit();
    }
}
$query = "SELECT leaves.*, employeedetail.EmpFirstName, employeedetail.EmpLastName 
          FROM leaves 
          JOIN employeedetail ON leaves.EmployeeID = employeedetail.ID 
          ORDER BY leaves.ID DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Leave Requests</title>
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
        
        .content-table { width: 100%; margin-top: 20px; border-collapse: collapse; background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .content-table th, .content-table td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        .content-table th { background-color: #f2f2f2; }
        .action-links a { margin-right: 10px; text-decoration: none; font-weight: bold; }
        .approve-link { color: #28a745; }
        .reject-link { color: #e74c3c; }
        .status-approved { color: green; font-weight: bold; }
        .status-rejected { color: red; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
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
            <span>Leave Management</span>
            <div class="logout">
                <a href="logout.php">Logout</a>
            </div>
        </div>
        
        <div style="margin-top: 20px;">
            <h1>🗓️Leave Requests</h1>
            <table class="content-table">
                <thead>
                    <tr>
                        <th>Requested By</th>
                        <th>Reason</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $status_class = '';
                            if ($row['Status'] == 'Approved') $status_class = 'status-approved';
                            elseif ($row['Status'] == 'Rejected') $status_class = 'status-rejected';
                            else $status_class = 'status-pending';

                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['EmpFirstName'] . ' ' . $row['EmpLastName']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['LeaveReason']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['StartDate']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['EndDate']) . "</td>";
                            echo "<td class='$status_class'>" . htmlspecialchars($row['Status']) . "</td>";
                            echo "<td class='action-links'>";
                            if ($row['Status'] == 'Pending') {
                                echo "<a href='leaves.php?action=approve&id=" . $row['ID'] . "' class='approve-link'>Approve</a>";
                                echo "<a href='leaves.php?action=reject&id=" . $row['ID'] . "' class='reject-link'>Reject</a>";
                            } else {
                                echo "N/A";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' style='text-align:center;'>No leave requests found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>