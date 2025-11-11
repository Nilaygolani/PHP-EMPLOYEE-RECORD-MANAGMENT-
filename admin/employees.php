<?php
session_start();
include('../db_connect.php');

if (!isset($_SESSION['aid'])) {
    header("Location: index.php");
    exit();
}

$search_query = '';
if (isset($_GET['search_query'])) {
    $search_query = trim($_GET['search_query']);
}

$query = "SELECT ID, NTMCode, EmpFirstName, EmpLastName, EmpDesignation, EmpEmail, JoiningDate, EmpSalary, TotalLeaves, ProfilePhoto FROM employeedetail";

if (!empty($search_query)) {
    $search_term = "%" . $search_query . "%";
    $query .= " WHERE EmpFirstName LIKE ? OR EmpLastName LIKE ?";
}

$query .= " ORDER BY RegistrationDate DESC";

$stmt = $conn->prepare($query);
if (!empty($search_query)) {
    $stmt->bind_param("ss", $search_term, $search_term);
}
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; margin: 0; background-color: #f4f7f6; }
        .sidebar { width: 250px; background-color: #2c3e50; color: white; position: fixed; height: 100%; padding-top: 20px; }
        .sidebar h2 { text-align: center; }
        .sidebar ul { list-style-type: none; padding: 0; }
        .sidebar ul li { padding: 15px 20px; border-bottom: 1px solid #34495e; }
        .sidebar ul li a { color: white; text-decoration: none; }
        .main-content { margin-left: 260px; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; background-color: white; padding: 10px 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .employee-table { width: 100%; margin-top: 20px; border-collapse: collapse; background-color: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .employee-table th, .employee-table td { border: 1px solid #ddd; padding: 12px; text-align: left; vertical-align: middle; }
        .employee-table th { background-color: #f2f2f2; }
        .add-employee-btn { background-color: #28a745; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none; font-weight: bold; }
        .search-form { margin-top: 20px; margin-bottom: 20px; background: white; padding: 15px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: flex; }
        .search-form input[type="text"] { flex-grow: 1; padding: 10px; border: 1px solid #ccc; border-radius: 4px 0 0 4px; }
        .search-form button { padding: 10px 20px; border: none; background-color: #007bff; color: white; cursor: pointer; border-radius: 0 4px 4px 0; }
        
        
        .profile-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
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
            </div>
        
        <div style="margin-top: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h1>👨‍💻Employee List</h1>
                <a href="add-employee.php" class="add-employee-btn">+ Add New Employee</a>
            </div>

            <form class="search-form" method="get" action="">
                <input type="text" name="search_query" placeholder="Search by first or last name..." value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit">Search</button>
            </form>

            <table class="employee-table">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Emp Id</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Designation</th>
                        <th>Email</th>
                        <th>Joining Date</th>
                        <th>Salary</th>
                        <th>Total Leaves</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                       
                            echo "<td><img src='../employee/profile_pics/" . htmlspecialchars($row['ProfilePhoto']) . "' class='profile-pic' onerror=\"this.onerror=null; this.src='../profile_pics/default_avatar.png';\"></td>";
                            
                            echo "<td>" . htmlspecialchars($row['NTMCode']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['EmpFirstName']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['EmpLastName']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['EmpDesignation']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['EmpEmail']) . "</td>";
                            echo "<td>" . date("d M, Y", strtotime($row['JoiningDate'])) . "</td>";
                            echo "<td>" . htmlspecialchars($row['EmpSalary']) . "</td>"; 
                            echo "<td>" . htmlspecialchars($row['TotalLeaves']) . "</td>";
                            echo "<td><a href='edit-employee.php?id=" . $row['ID'] . "'>Edit</a> | <a href='delete-employee.php?id=" . $row['ID'] . "' onclick=\"return confirm('Are you sure you want to delete this employee?');\">Delete</a></td>";
                            echo "</tr>";
                        }
                    } else {
                      
                        echo "<tr><td colspan='10' style='text-align:center;'>No employees found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>