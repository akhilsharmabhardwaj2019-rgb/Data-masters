<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../auth/login.html");
    exit;
}
include '../config/db.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard - Bharat Data Recovery</title>
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .dashboard-container {
            padding: 40px 5%;
            margin: 0 auto;
        }

        .dashboard-section {
            background: var(--bg-card);
            padding: 30px;
            border-radius: 16px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        h3 {
            color: var(--primary);
            margin-bottom: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 10px;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="logo">
            <img src="../assets/images/Logo.png.jpeg" alt="Bharat Data Recovery Logo">
            <span>Bharat Data Recovery</span>
        </div>
        <div class="nav-links">
            <span style="color: var(--primary);">Admin Panel</span>
            <a href="../auth/logout.php" class="btn btn-secondary"
                style="padding: 5px 15px; font-size: 0.9rem;">Logout</a>
        </div>
    </nav>

    <div class="dashboard-container">

        <!-- UPLOAD SECTION -->
        <div class="dashboard-section">
            <h3>Upload Image Asset</h3>
            <form action="upload_image.php" method="post" enctype="multipart/form-data" style="max-width: 500px;">
                <input type="file" name="image" required style="padding: 10px; background: rgba(0,0,0,0.2);">
                <input type="submit" name="upload" value="Upload Image" class="btn btn-primary"
                    style="width: auto; margin-top: 10px; cursor: pointer;">
            </form>
        </div>

        <!-- QUERIES SECTION -->
        <div class="dashboard-section">
            <h3>User Queries</h3>
            <?php
            $queries = mysqli_query($conn, "SELECT p.id, u.name, p.problem, p.status, p.solution FROM problems p JOIN users u ON p.user_id = u.id");

            if (!$queries) {
                echo "<p style='color: red;'>Error loading queries: " . mysqli_error($conn) . "</p>";
            } else {
                echo "<table><thead><tr><th>ID</th><th>User</th><th>Problem</th><th>Status</th><th>Action</th></tr></thead><tbody>";
                while ($row = mysqli_fetch_assoc($queries)) {
                    echo "<tr>
                        <td>#" . $row['id'] . "</td>
                        <td>" . htmlspecialchars($row['name']) . "</td>
                        <td>" . htmlspecialchars($row['problem']) . "</td>
                        <td>
                            <span style='padding: 4px 10px; border-radius: 4px; background: " . ($row['status'] == 'Solved' ? 'green' : '#ff9800') . "; color: white; font-size: 0.8rem;'>
                                " . $row['status'] . "
                            </span>
                        </td>
                        <td>";

                    if ($row['status'] == 'Pending') {
                        echo "<form action='update_solution.php' method='post'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <textarea name='solution' required placeholder='Write solution...' style='width: 100%; height: 60px; padding: 5px; margin-bottom: 5px;'></textarea>
                                <input type='submit' value='Send Solution' class='btn btn-primary' style='padding: 5px 10px; font-size: 0.8rem;'>
                              </form>";
                    } else {
                        echo "<div style='max-width: 300px; font-size: 0.9rem; color: #ccc;'>" . htmlspecialchars($row['solution'] ?? '') . "</div>";
                    }

                    echo "</td>
                      </tr>";
                }
                echo "</tbody></table>";
            }

            ?>
        </div>

        <!-- GALLERY MANAGEMENT SECTION -->
        <div class="dashboard-section">
            <h3>Gallery Management</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px;">
                <?php
                $dir = "../assets/images/gallery/";
                if (is_dir($dir)) {
                    $files = glob($dir . "*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE);
                    if ($files) {
                        foreach ($files as $image) {
                            $filename = basename($image);
                            echo '<div style="background: rgba(0,0,0,0.2); padding: 10px; border-radius: 8px; text-align: center;">';
                            echo '<img src="' . $image . '" style="width: 100%; height: 100px; object-fit: cover; border-radius: 4px; margin-bottom: 10px;">';
                            echo '<form action="delete_image.php" method="post" onsubmit="return confirm(\'Are you sure you want to delete this image?\');">';
                            echo '<input type="hidden" name="image" value="' . $filename . '">';
                            echo '<button type="submit" style="background: #ff4444; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">Delete</button>';
                            echo '</form>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p style="color: #ccc;">No images in gallery.</p>';
                    }
                }
                ?>
            </div>
        </div>

        <!-- REVIEWS MANAGEMENT SECTION -->
        <div class="dashboard-section">
            <h3>Review Management</h3>
            <?php
            $reviews = mysqli_query($conn, "SELECT r.id, u.name, r.rating, r.comment, r.created_at FROM reviews r JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC");

            if (!$reviews) {
                // Check if table exists first, if not showing specific error might be helpful or just ignore if feature not fully ready
                if (mysqli_errno($conn) == 1146) {
                    echo "<p style='color: sandybrown;'>Reviews table not found. Please setup database.</p>";
                } else {
                    echo "<p style='color: red;'>Error loading reviews: " . mysqli_error($conn) . "</p>";
                }
            } else {
                if (mysqli_num_rows($reviews) > 0) {
                    echo "<table><thead><tr><th>User</th><th>Rating</th><th>Comment</th><th>Date</th><th>Action</th></tr></thead><tbody>";
                    while ($review = mysqli_fetch_assoc($reviews)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($review['name']) . "</td>";
                        echo "<td>" . $review['rating'] . "/5</td>";
                        echo "<td>" . htmlspecialchars($review['comment']) . "</td>";
                        echo "<td>" . $review['created_at'] . "</td>";
                        echo "<td>";
                        echo "<form action='delete_review.php' method='post' onsubmit='return confirm(\"Are you sure you want to delete this review?\");'>";
                        echo "<input type='hidden' name='id' value='" . $review['id'] . "'>";
                        echo "<button type='submit' style='background: #ff4444; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;'>Delete</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p style='color: #ccc;'>No reviews yet.</p>";
                }
            }
            ?>
        </div>

    </div>

</body>

</html>