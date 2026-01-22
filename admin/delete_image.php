<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../auth/login.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['image'])) {
    $image = basename($_POST['image']);
    $filepath = "../assets/images/gallery/" . $image;

    if (file_exists($filepath)) {
        if (unlink($filepath)) {
            echo "<script>alert('Image deleted successfully.'); window.location.href='admin_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error: Could not delete image.'); window.location.href='admin_dashboard.php';</script>";
        }
    } else {
        echo "<script>alert('Error: Image not found.'); window.location.href='admin_dashboard.php';</script>";
    }
} else {
    header("Location: admin_dashboard.php");
    exit;
}
?>