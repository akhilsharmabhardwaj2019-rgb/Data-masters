<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.html");
    exit;
}
include '../config/db.php';

if (isset($_POST['submit'])) {
    $email = $_SESSION['user'];
    $problem = mysqli_real_escape_string($conn, $_POST['problem']);

    $user = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
    $user_data = mysqli_fetch_assoc($user);
    $user_id = $user_data['id'];

    mysqli_query($conn, "INSERT INTO problems (user_id, problem) VALUES ('$user_id', '$problem')");

    // Optional: SMS notification code here
    // send_sms($mobile_number, "Your problem has been submitted successfully!");

    echo "<script>alert('Problem submitted successfully'); window.location='user_dashboard.php';</script>";
}
?>