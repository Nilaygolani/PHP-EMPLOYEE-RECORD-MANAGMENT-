<?php
session_start();
include('../db_connect.php');
if (!isset($_SESSION['eid'])) {
    header("Location: index.php");
    exit();
}

$employee_id = $_SESSION['eid'];
$error_message = '';
$success_message = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $update_success = false;
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $ntmcode = $_POST['ntmcode'];
    $designation = $_POST['designation']; 
    $stmt_text = $conn->prepare("UPDATE employeedetail SET EmpFirstName = ?, EmpLastName = ?, NTMCode = ?, EmpDesignation = ? WHERE ID = ?");
    $stmt_text->bind_param("ssssi", $fname, $lname, $ntmcode, $designation, $employee_id);
    
    if ($stmt_text->execute()) {
        $update_success = true;
    }
    $stmt_text->close();

    
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $target_dir = "profile_pics/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', ];

        if (in_array($file_extension, $allowed_types)) {
            $new_filename = "profile_" . $employee_id . "_" . time() . "." . $file_extension;
            $target_file = $target_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target_file)) {
                $stmt_photo = $conn->prepare("UPDATE employeedetail SET ProfilePhoto = ? WHERE ID = ?");
                $stmt_photo->bind_param("si", $new_filename, $employee_id);
                if ($stmt_photo->execute()) {
                    $update_success = true;
                }
                $stmt_photo->close();
            } else {
                $error_message = "error profile update";
                $update_success = false;
            }
        } else {
            $error_message = "Upload = JPG, JPEG, PNG ";
            $update_success = false;
        }
    }

    if ($update_success && empty($error_message)) {
        $success_message = "profile succesfully update ";
    }
}


$stmt_emp = $conn->prepare("SELECT EmpFirstName, EmpLastName, NTMCode, ProfilePhoto, EmpDesignation FROM employeedetail WHERE ID = ?");
$stmt_emp->bind_param("i", $employee_id);
$stmt_emp->execute();
$result_emp = $stmt_emp->get_result();
$employee = $result_emp->fetch_assoc();
$stmt_emp->close();
?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; background-color: #f4f7f6; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; box-sizing: border-box; }
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 450px; }
        h2 { text-align: center; margin-bottom: 25px; color: #2c3e50; }
        .profile-pic-container { text-align: center; margin-bottom: 20px; }
        .profile-pic-preview { width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #ecf0f1; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: bold; color: #555; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        .btn { background-color: #2c3e50; color: white; padding: 12px 15px; border: none; border-radius: 5px; cursor: pointer; width: 100%; font-size: 16px; transition: background-color 0.2s; }
        .btn:hover { background-color: #34495e; }
        .back-link { display: block; text-align: center; margin-top: 20px; color: #3498db; text-decoration: none; }
        .message { padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; }
        .success { background-color: #d4edda; color: #155724; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Profile</h2>

        <?php if ($success_message): ?><p class="message success"><?php echo $success_message; ?></p><?php endif; ?>
        <?php if ($error_message): ?><p class="message error"><?php echo $error_message; ?></p><?php endif; ?>
        
        <div class="profile-pic-container">
            <img src="profile_pics/<?php echo htmlspecialchars($employee['ProfilePhoto']); ?>" alt="Profile Picture" class="profile-pic-preview" onerror="this.onerror=null; this.src='profile_pics/default_avatar.png';">
        </div>

        <form action="edit-profile.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="fname">First Name</label>
                <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($employee['EmpFirstName']); ?>" required>
            </div>
            <div class="form-group">
                <label for="lname">Last Name</label>
                <input type="text" id="lname" name="lname" value="<?php echo htmlspecialchars($employee['EmpLastName']); ?>" required>
            </div>
            
            
            <div class="form-group">
                <label for="designation">Designation</label>
                <input type="text" id="designation" name="designation" value="<?php echo htmlspecialchars($employee['EmpDesignation']); ?>">
            </div>

            <div class="form-group">
                <label for="profile_photo">Change Profile Photo (Optional)</label>
                <input type="file" id="profile_photo" name="profile_photo" accept="image/*">
            </div>
            <button type="submit" class="btn">Update Profile</button>
        </form>
        <a href="dashboard.php" class="back-link">Home</a>
    </div>
</body>
</html>