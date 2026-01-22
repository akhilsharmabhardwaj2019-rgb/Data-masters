<?php
session_start();
include '../config/db.php';

if (isset($_POST['login'])) {
    $login_id = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);

    // Check admin (Admins use Username)
    $admin_check = mysqli_query($conn, "SELECT * FROM admin WHERE username='$login_id' AND password='$password'");
    if (mysqli_num_rows($admin_check) > 0) {
        $_SESSION['admin'] = $login_id;
        header("Location: ../admin/admin_dashboard.php");
        exit;
    }

    // Check user (Users use Email)
    $user_check = mysqli_query($conn, "SELECT * FROM users WHERE email='$login_id' AND password='$password'");
    if (mysqli_num_rows($user_check) > 0) {
        $_SESSION['user'] = $login_id;
        header("Location: ../user/user_dashboard.php");
        exit;
    }

    echo "<script>alert('Invalid username or password'); window.location='login.html';</script>";
}
?>