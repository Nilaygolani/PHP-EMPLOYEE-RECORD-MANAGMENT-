<?php
session_start();
include('../db_connect.php');

// सुनिश्चित करें कि कर्मचारी लॉग इन है
if (!isset($_SESSION['eid'])) {
    header("Location: index.php");
    exit();
}

// सुनिश्चित करें कि फॉर्म POST मेथड से सबमिट हुआ है
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // फॉर्म से डेटा प्राप्त करें
    $task_id = $_POST['task_id'];
    $new_status = $_POST['new_status'];
    $employee_id = $_SESSION['eid'];

    // सुरक्षा जाँच: सुनिश्चित करें कि यह टास्क इसी कर्मचारी का है
    $stmt_check = $conn->prepare("SELECT ID FROM tasks WHERE ID = ? AND EmployeeID = ?");
    $stmt_check->bind_param("ii", $task_id, $employee_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows == 1) {
        // अगर टास्क इसी कर्मचारी का है, तो स्टेटस अपडेट करें
        $stmt_update = $conn->prepare("UPDATE tasks SET Status = ? WHERE ID = ?");
        $stmt_update->bind_param("si", $new_status, $task_id);
        
        if ($stmt_update->execute()) {
            // सफलतापूर्वक अपडेट होने पर डैशबोर्ड पर वापस भेजें
            header("Location: dashboard.php?status=success");
        } else {
            // एरर आने पर डैशबोर्ड पर वापस भेजें
            header("Location: dashboard.php?status=error");
        }
        $stmt_update->close();

    } else {
        // अगर कोई और कर्मचारी किसी और का टास्क अपडेट करने की कोशिश करे
        header("Location: dashboard.php?status=auth_error");
    }
    $stmt_check->close();

} else {
    // अगर कोई सीधे इस पेज पर आने की कोशिश करे
    header("Location: dashboard.php");
}
exit();
?>