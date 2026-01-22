<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.html");
    exit;
}
include '../config/db.php';
$email = $_SESSION['user'];
$user_query = mysqli_query($conn, "SELECT id, name FROM users WHERE email='$email'");
$user_data = mysqli_fetch_assoc($user_query);
$user_id = $user_data['id'];
$user_name = $user_data['name'];
?>

<!DOCTYPE html>
<html>

<head>
    <title>User Dashboard - Bharat Data Recovery</title>
    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .dashboard-container {
            padding: 100px 5%;
            max-width: 800px;
            margin: 0 auto;
        }

        .dashboard-card {
            background: var(--bg-card);
            padding: 40px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.05);
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
            <span style="color: var(--primary);">Welcome, <?php echo htmlspecialchars($user_name); ?></span>
            <a href="../auth/logout.php" class="btn btn-secondary"
                style="padding: 5px 15px; font-size: 0.9rem;">Logout</a>
        </div>
    </nav>

    <!-- DASHBOARD CONTENT -->
    <div class="dashboard-container">
        <div class="dashboard-card">
            <h2 style="color: var(--primary); margin-bottom: 20px;">Submit a New Problem</h2>
            <form action="submit_problem.php" method="post">
                <label style="color: var(--text-dim);">Describe your issue in detail:</label>
                <textarea name="problem" placeholder="E.g., My hard drive is clicking and not detected..." rows="5"
                    required></textarea>
                <input type="submit" name="submit" value="Submit Problem" class="btn btn-primary"
                    style="width: auto; cursor: pointer;">
            </form>
        </div>

        <div class="dashboard-card" style="margin-top: 30px;">
            <h2 style="color: var(--primary); margin-bottom: 20px;">My Queries</h2>
            <?php
            $my_problems = mysqli_query($conn, "SELECT p.*, r.id as review_id, r.rating FROM problems p LEFT JOIN reviews r ON p.id = r.problem_id WHERE p.user_id='$user_id' ORDER BY p.created_at DESC");
            if (mysqli_num_rows($my_problems) > 0) {
                echo "<table style='width: 100%; border-collapse: collapse;'>
                        <thead>
                            <tr style='text-align: left; color: var(--text-dim); border-bottom: 1px solid rgba(255,255,255,0.1);'>
                                <th style='padding: 10px;'>Problem</th>
                                <th style='padding: 10px;'>Status</th>
                                <th style='padding: 10px;'>Solution</th>
                                <th style='padding: 10px;'>Review</th>
                            </tr>
                        </thead>
                        <tbody>";
                while ($row = mysqli_fetch_assoc($my_problems)) {
                    $status_color = ($row['status'] == 'Solved') ? 'green' : '#ff9800';

                    $review_html = '-';
                    if ($row['status'] == 'Solved') {
                        if ($row['review_id']) {
                            $review_html = '<span style="color: gold;">★</span> ' . $row['rating'] . '/5';
                        } else {
                            $review_html = '<a href="write_review.php?problem_id=' . $row['id'] . '" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem; text-decoration: none;">Rate Us</a>';
                        }
                    }

                    echo "<tr style='border-bottom: 1px solid rgba(255,255,255,0.05);'>
                            <td style='padding: 15px 10px;'>" . htmlspecialchars($row['problem']) . "</td>
                            <td style='padding: 15px 10px;'>
                                <span style='padding: 4px 10px; border-radius: 4px; background: $status_color; color: white; font-size: 0.8rem;'>
                                    " . $row['status'] . "
                                </span>
                            </td>
                            <td style='padding: 15px 10px; color: #ccc;'>" .
                        ($row['solution'] ? htmlspecialchars($row['solution']) : '<em>Pending admin review...</em>') .
                        "</td>
                            <td style='padding: 15px 10px;'>" . $review_html . "</td>
                          </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p style='color: var(--text-dim);'>You haven't submitted any problems yet.</p>";
            }
            ?>
        </div>
    </div>

</body>

</html>