<?php
session_start();
include('../db_connect.php');


if (!isset($_SESSION['aid'])) {
    header("Location: index.php");
    exit();
}


if (isset($_GET['id'])) {
    $employee_id = $_GET['id'];

   
    $stmt = $conn->prepare("DELETE FROM employeedetail WHERE ID = ?");
    $stmt->bind_param("i", $employee_id);

 
    if ($stmt->execute()) {
        header("Location: employees.php?status=deleted");
        exit();
    } else {
       
        header("Location: employees.php?status=error");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: employees.php");
    exit();
}
?>