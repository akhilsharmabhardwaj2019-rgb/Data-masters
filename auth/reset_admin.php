<?php
include '../config/db.php';

$username = 'admin';
$password = md5('admin123');

// Check if admin exists
$check = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");

if (mysqli_num_rows($check) > 0) {
    // Update existing
    $sql = "UPDATE admin SET password='$password' WHERE username='$username'";
    if (mysqli_query($conn, $sql)) {
        echo "Admin password updated successfully provided! <br>";
    } else {
        echo "Error updating admin: " . mysqli_error($conn);
    }
} else {
    // Insert new
    $sql = "INSERT INTO admin (username, password) VALUES ('$username', '$password')";
    if (mysqli_query($conn, $sql)) {
        echo "Admin user created successfully! <br>";
    } else {
        echo "Error creating admin: " . mysqli_error($conn);
    }
}

echo "<br><strong>Credentials:</strong><br>";
echo "Username: admin<br>";
echo "Password: admin123<br>";
echo "<br><a href='admin_login.html'>Go to Admin Login</a>";
?>