<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../auth/login.html");
    exit;
}
include '../config/db.php';

if (isset($_POST['upload'])) {
    $target_dir = "../assets/images/gallery/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($_FILES["image"]["name"]);

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        echo "<script>alert('Image uploaded successfully'); window.location='admin_dashboard.php';</script>";
        // Optional: SMS notification to mobile
    } else {
        echo "<script>alert('Error uploading image'); window.location='admin_dashboard.php';</script>";
    }
}
?>