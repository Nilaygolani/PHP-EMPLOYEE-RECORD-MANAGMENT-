<?php
session_start();
include('../db_connect.php');


if (!isset($_SESSION['aid'])) {
    header("Location: index.php");
    exit();
}

$employee_id = $_GET['id'];
$error_message = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $ntmcode = $_POST['ntmcode'];
    $joindate = $_POST['joindate'];
    $salary = $_POST['salary'];
    $designation = $_POST['designation'];
    $experience = $_POST['experience'];
    $totalleaves = $_POST['totalleaves'];
    
    
    $stmt = $conn->prepare("UPDATE employeedetail SET EmpFirstName=?, EmpLastName=?, EmpEmail=?, NTMCode=?, JoiningDate=?, EmpSalary=?, EmpDesignation=?, EmpExperience=?, TotalLeaves=? WHERE ID=?");
    
    $stmt->bind_param("sssssdsdii", $fname, $lname, $email, $ntmcode, $joindate, $salary, $designation, $experience, $totalleaves, $employee_id);

    // === यहाँ पर try...catch ब्लॉक जोड़ा गया है ताकि एरर को हैंडल किया जा सके ===
    try {
        if ($stmt->execute()) {
            header("Location: employees.php?status=updated");
            exit();
        }
    } catch (mysqli_sql_exception $e) {
        // MySQL में डुप्लीकेट एंट्री का एरर कोड 1062 होता है
        if ($e->getCode() == 1062) {
            $error_message = "This Email ID already exists. Please use a different one.";
        } else {
            // किसी और डेटाबेस एरर के लिए
            $error_message = "Error: Could not update employee details.";
        }
    }
    // === try...catch ब्लॉक यहाँ खत्म होता है ===
    
    $stmt->close();
}

// यह क्वेरी पहले से ही '*' (सभी कॉलम) ला रही है, इसलिए TotalLeaves अपने आप आ जाएँगे
$query = "SELECT * FROM employeedetail WHERE ID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

if (!$employee) {
    echo "Employee not found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
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
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
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
            <span>Employee Management</span>
            <div class="logout">
                <a href="logout.php">Logout</a>
            </div>
        </div>
        
        <div class="form-container">
            <a href="employees.php" class="btn-back">&larr; Back to Employee List</a>
            <h1>Edit Employee Details</h1>
            
            <?php if(!empty($error_message)) { echo "<p style='color:red;'>".$error_message."</p>"; } ?>
            
            <form action="edit-employee.php?id=<?php echo $employee_id; ?>" method="post">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="fname" value="<?php echo htmlspecialchars($employee['EmpFirstName']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="lname" value="<?php echo htmlspecialchars($employee['EmpLastName']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($employee['EmpEmail']); ?>" required>
                </div>
                <div class="form-group">
                    <label>NTM Code</label>
                    <input type="text" name="ntmcode" value="<?php echo htmlspecialchars($employee['NTMCode']); ?>">
                </div>
                <div class="form-group">
                    <label>Joining Date</label>
                    <input type="date" name="joindate" value="<?php echo htmlspecialchars($employee['JoiningDate']); ?>">
                </div>
                <div class="form-group">
                    <label>Salary</label>
                    <input type="number" name="salary" step="0.01" value="<?php echo htmlspecialchars($employee['EmpSalary']); ?>">
                </div>
                <div class="form-group">
                    <label>Designation</label>
                    <input type="text" name="designation" value="<?php echo htmlspecialchars($employee['EmpDesignation']); ?>">
                </div>
                <div class="form-group">
                    <label>Experience</label>
                    <input type="text" name="experience" value="<?php echo htmlspecialchars($employee['EmpExperience']); ?>">
                </div>
                
                <div class="form-group">
                    <label>Total Allocated Leaves</label>
                    <input type="number" name="totalleaves" value="<?php echo htmlspecialchars($employee['TotalLeaves']); ?>" required>
                </div>

                <button type="submit" class="btn-submit">Update Details</button>
            </form>
        </div>
    </div>
</body>
</html>