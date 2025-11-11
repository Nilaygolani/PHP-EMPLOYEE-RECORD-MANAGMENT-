<?php
session_start();
include('../db_connect.php');

// Admin session check
if (!isset($_SESSION['aid'])) {
    header("Location: index.php");
    exit();
}

$errors = [];
$old_data = [];

// Check for errors from a previous failed submission
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}
if (isset($_SESSION['old_data'])) {
    $old_data = $_SESSION['old_data'];
    unset($_SESSION['old_data']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Store submitted data to repopulate form if validation fails
    $old_data = $_POST;

    // 1. Sanitize and Validate Inputs (Server-Side Validation)
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $password = $_POST['password']; // Get plain password
    $joindate = trim($_POST['joindate']);

    // Optional fields
    $ntmcode = trim($_POST['ntmcode']);
    $salary = trim($_POST['salary']);
    $designation = trim($_POST['designation']);
    $experience = trim($_POST['experience']);

    if (empty($fname)) {
        $errors['fname'] = "First name is required.";
    }
    if (empty($lname)) {
        $errors['lname'] = "Last name is required.";
    }
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters long.";
    }
    if (empty($joindate)) {
        $errors['joindate'] = "Joining date is required.";
    } elseif ($joindate > date('Y-m-d')) {
        $errors['joindate'] = "Joining date cannot be in the future.";
    }

    // 2. If there are errors, redirect back to the form
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_data'] = $_POST;
        header("Location: add-employee.php");
        exit();
    }
    
    // --- REMOVED ---
    // Password hashing line has been removed.
    // $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 4. Prepare and Execute SQL if validation passes
    try {
        $stmt = $conn->prepare("INSERT INTO employeedetail (EmpFirstName, EmpLastName, EmpEmail, NTMCode, JoiningDate, EmpSalary, EmpDesignation, EmpExperience, EmpPassword) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param(
            "sssssdsds", 
            $fname, 
            $lname, 
            $email, 
            $ntmcode, 
            $joindate, 
            $salary, 
            $designation, 
            $experience, 
            $password // --- CHANGED: Using plain $password variable instead of $hashed_password
        );

        if ($stmt->execute()) {
            header("Location: employees.php?status=success");
            exit();
        }
        $stmt->close();
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() === 1062) {
            $errors['email'] = "An employee with this email already exists.";
        } else {
            $errors['general'] = "Database error: Could not add employee. Please try again.";
        }
        $_SESSION['errors'] = $errors;
        $_SESSION['old_data'] = $_POST;
        header("Location: add-employee.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Employee</title>
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
        .btn-submit { background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .btn-back { display: inline-block; margin-bottom: 20px; color: #3498db; text-decoration: none; }
        .error-message { color: red; font-size: 0.9em; margin-top: 5px; }
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
            <h1>Add New Employee</h1>
            
            <?php if(isset($errors['general'])): ?>
                <p class="error-message"><?php echo $errors['general']; ?></p>
            <?php endif; ?>
            
            <form action="add-employee.php" method="post" id="addEmployeeForm">
                <div class="form-group">
                    <label for="fname">First Name</label>
                    <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($old_data['fname'] ?? ''); ?>">
                    <?php if(isset($errors['fname'])): ?><p class="error-message"><?php echo $errors['fname']; ?></p><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="lname">Last Name</label>
                    <input type="text" id="lname" name="lname" value="<?php echo htmlspecialchars($old_data['lname'] ?? ''); ?>">
                    <?php if(isset($errors['lname'])): ?><p class="error-message"><?php echo $errors['lname']; ?></p><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($old_data['email'] ?? ''); ?>">
                    <?php if(isset($errors['email'])): ?><p class="error-message"><?php echo $errors['email']; ?></p><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="ntmcode">NTM Code</label>
                    <input type="text" id="ntmcode" name="ntmcode" value="<?php echo htmlspecialchars($old_data['ntmcode'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="joindate">Joining Date</label>
                    <input type="date" id="joindate" name="joindate" value="<?php echo htmlspecialchars($old_data['joindate'] ?? ''); ?>">
                    <?php if(isset($errors['joindate'])): ?><p class="error-message"><?php echo $errors['joindate']; ?></p><?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="salary">Salary</label>
                    <input type="number" id="salary" name="salary" step="0.01" value="<?php echo htmlspecialchars($old_data['salary'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="designation">Designation</label>
                    <input type="text" id="designation" name="designation" value="<?php echo htmlspecialchars($old_data['designation'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="experience">Experience</label>
                    <input type="text" id="experience" name="experience" value="<?php echo htmlspecialchars($old_data['experience'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password">
                    <?php if(isset($errors['password'])): ?><p class="error-message"><?php echo $errors['password']; ?></p><?php endif; ?>
                </div>
                <button type="submit" class="btn-submit">Add Employee</button>
            </form>
        </div>
    </div>

<script>
document.getElementById('addEmployeeForm').addEventListener('submit', function(event) {
    document.querySelectorAll('.js-error').forEach(e => e.remove());

    let isValid = true;
    
    const fname = document.getElementById('fname');
    const lname = document.getElementById('lname');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const joindate = document.getElementById('joindate');

    const showError = (input, message) => {
        const error = document.createElement('p');
        error.textContent = message;
        error.className = 'error-message js-error';
        input.parentNode.appendChild(error);
        isValid = false;
    };

    if (fname.value.trim() === '') showError(fname, 'First name cannot be empty.');
    if (lname.value.trim() === '') showError(lname, 'Last name cannot be empty.');
    if (email.value.trim() === '') {
        showError(email, 'Email cannot be empty.');
    } else if (!/^\S+@\S+\.\S+$/.test(email.value)) {
        showError(email, 'Please enter a valid email address.');
    }
    if (password.value.trim() === '') {
        showError(password, 'Password cannot be empty.');
    } else if (password.value.length < 8) {
        showError(password, 'Password must be at least 8 characters long.');
    }

    const today = new Date().toISOString().split('T')[0]; 
    if (joindate.value === '') {
        showError(joindate, 'Joining date is required.');
    } else if (joindate.value > today) {
        showError(joindate, 'Joining date cannot be in the future.');
    }

    if (!isValid) {
        event.preventDefault();
    }
});
</script>

</body>
</html>