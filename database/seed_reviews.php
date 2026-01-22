<?php
include '../config/db.php';

// 1. Create Table if not exists
$create_table = "CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    problem_id INT NULL,
    user_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(user_id) REFERENCES users(id)
)";
// Note: problem_id made nullable for dummy reviews, or we need existing problem IDs. 
// For simplicity in seeding, let's relax the foreign key constraint or ensure we have users.

if (!mysqli_query($conn, $create_table)) {
    die("Error creating table: " . mysqli_error($conn));
}

// 2. Check if users exist to attach reviews to
$user_check = mysqli_query($conn, "SELECT id FROM users LIMIT 1");
if (mysqli_num_rows($user_check) == 0) {
    // Create a dummy user
    mysqli_query($conn, "INSERT INTO users (name, email, password) VALUES ('Amit Sharma', 'amit@example.com', '" . md5('password') . "')");
    $user_id = mysqli_insert_id($conn);
} else {
    $row = mysqli_fetch_assoc($user_check);
    $user_id = $row['id'];
}

// 3. Insert Dummy Reviews
$reviews = [
    [5, "Best service ever! Recovered all my wedding photos from a formatted card."],
    [5, "Very professional and fast. Highly recommended for SSD recovery."],
    [4, "Good service, took a bit longer than expected but got my data back."]
];

$count = 0;
foreach ($reviews as $rev) {
    $rating = $rev[0];
    $comment = mysqli_real_escape_string($conn, $rev[1]);
    // problem_id is NULL for these dummy reviews (assumes schema allows it or we skip FK for problem)
    // Actually my previous schema had problem_id NOT NULL. I should probably fix that for dummy data or insert a dummy problem.

    // Let's check schema again. If strict, we need a problem.
    // Let's insert a dummy problem first.
    mysqli_query($conn, "INSERT INTO problems (user_id, problem, status, solution) VALUES ('$user_id', 'Dummy failure', 'Solved', 'Fixed')");
    $problem_id = mysqli_insert_id($conn);

    $sql = "INSERT INTO reviews (problem_id, user_id, rating, comment) VALUES ('$problem_id', '$user_id', '$rating', '$comment')";
    if (mysqli_query($conn, $sql)) {
        $count++;
    }
}

echo "Setup complete. specific table created and $count dummy reviews inserted.";
?>