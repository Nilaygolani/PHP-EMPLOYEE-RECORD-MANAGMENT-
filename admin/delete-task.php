<?php
session_start();
include('../db_connect.php');

if (!isset($_SESSION['aid'])) {
    header("Location: index.php");
    exit();
}


if (isset($_GET['id'])) {
    $task_id = $_GET['id'];

   
    $stmt = $conn->prepare("DELETE FROM tasks WHERE ID = ?");
    $stmt->bind_param("i", $task_id);

    
    if ($stmt->execute()) {
        
        header("Location: tasks.php?status=deleted");
        exit();
    } else {
       
        header("Location: tasks.php?status=error");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
   
    header("Location: tasks.php");
    exit();
}
?>