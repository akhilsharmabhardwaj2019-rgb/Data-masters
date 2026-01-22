<?php
include '../config/db.php';

$id = mysqli_real_escape_string($conn, $_POST['id']);
$solution = mysqli_real_escape_string($conn, $_POST['solution']);

mysqli_query($conn, "UPDATE problems SET solution='$solution', status='Solved' WHERE id='$id'");

header("Location: admin_dashboard.php");
?>