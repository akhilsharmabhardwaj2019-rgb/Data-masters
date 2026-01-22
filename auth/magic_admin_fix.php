<?php
ob_start(); // Start buffer to prevent header errors
session_start();
include '../config/db.php';

$username = 'admin';
$password = md5('admin123');

// 1. Ensure admin exists and password is correct
$check = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");
if (mysqli_num_rows($check) > 0) {
    $sql = "UPDATE admin SET password='$password' WHERE username='$username'";
    mysqli_query($conn, $sql);
} else {
    $sql = "INSERT INTO admin (username, password) VALUES ('$username', '$password')";
    mysqli_query($conn, $sql);
}

// 2. Set Session directly
$_SESSION['admin'] = true; // Use boolean or username string depending on checks, admin_login.php sets boolean.
// Actually admin_login.php sets $_SESSION['admin'] = true; Wait, let me check admin_login.php again.
// admin_dashboard.php checks `if(!isset($_SESSION['admin']))`.
// login.php sets $_SESSION['admin'] = $username; (string)
// admin_login.php sets $_SESSION['admin'] = true; (boolean)
// This is inconsistent but dashboard just checks `isset`, so both work. 
// I will set it to the username string 'admin' to be more useful if needed later.
$_SESSION['admin'] = 'admin';

// 3. Redirect to dashboard
header("Location: ../admin/admin_dashboard.php");
exit;
?>