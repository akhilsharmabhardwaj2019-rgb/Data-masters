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

if (!isset($_GET['problem_id'])) {
    header("Location: user_dashboard.php");
    exit;
}

$problem_id = intval($_GET['problem_id']);

// Validate problem
$check_query = "SELECT * FROM problems WHERE id='$problem_id' AND user_id='$user_id' AND status='Solved'";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) == 0) {
    echo "<script>alert('Invalid Request or Problem not solved yet.'); window.location.href='user_dashboard.php';</script>";
    exit;
}

// Check if already reviewed
$review_check = mysqli_query($conn, "SELECT id FROM reviews WHERE problem_id='$problem_id'");
if (mysqli_num_rows($review_check) > 0) {
    echo "<script>alert('You have already reviewed this service.'); window.location.href='user_dashboard.php';</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating = intval($_POST['rating']);
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    if ($rating < 1 || $rating > 5) {
        $error = "Please select a valid rating.";
    } else {
        $insert_sql = "INSERT INTO reviews (problem_id, user_id, rating, comment) VALUES ('$problem_id', '$user_id', '$rating', '$comment')";
        if (mysqli_query($conn, $insert_sql)) {
            echo "<script>alert('Thank you for your feedback!'); window.location.href='user_dashboard.php';</script>";
            exit;
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Write a Review - Bharat Data Recovery</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .review-container {
            padding: 100px 5%;
            max-width: 600px;
            margin: 0 auto;
        }

        .card {
            background: var(--bg-card);
            padding: 40px;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .rating-select {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 10px;
            border-radius: 8px;
            width: 100%;
            margin-bottom: 20px;
        }

        textarea {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            resize: vertical;
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <div class="logo">
            <img src="../assets/images/Logo.png.jpeg" alt="Bharat Data Recovery Logo">
            <span>Bharat Data Recovery</span>
        </div>
        <div class="nav-links">
            <a href="user_dashboard.php" class="btn btn-secondary">Back to functional dashboard</a>
        </div>
    </nav>

    <div class="review-container">
        <div class="card">
            <h2 style="color: var(--primary); margin-bottom: 20px;">Rate Our Service</h2>
            <?php if (isset($error))
                echo "<p style='color: red;'>$error</p>"; ?>
            <form method="post">
                <label style="color: var(--text-dim); display: block; margin-bottom: 10px;">Rating (1-5)</label>
                <select name="rating" class="rating-select" required>
                    <option value="5">5 - Excellent!</option>
                    <option value="4">4 - Very Good</option>
                    <option value="3">3 - Good</option>
                    <option value="2">2 - Fair</option>
                    <option value="1">1 - Poor</option>
                </select>

                <label style="color: var(--text-dim); display: block; margin-bottom: 10px;">Your Comments</label>
                <textarea name="comment" rows="5" placeholder="Tell us about your experience..."></textarea>

                <input type="submit" value="Submit Review" class="btn btn-primary"
                    style="margin-top: 20px; cursor: pointer;">
            </form>
        </div>
    </div>
</body>

</html>