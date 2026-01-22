<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../auth/login.html");
    exit;
}
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $sql = "DELETE FROM reviews WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Review deleted successfully.'); window.location.href='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error deleting review: " . mysqli_error($conn) . "'); window.location.href='admin_dashboard.php';</script>";
    }
} else {
    header("Location: admin_dashboard.php");
    exit;
}
?>