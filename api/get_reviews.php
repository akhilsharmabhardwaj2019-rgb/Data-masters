<?php
// Turn off error reporting for output to avoid breaking JSON
error_reporting(0);
header('Content-Type: application/json');

include '../config/db.php';

if (!$conn) {
    echo json_encode([]);
    exit;
}

// Fetch latest positive reviews
$sql = "SELECT r.rating, r.comment, u.name, r.created_at 
        FROM reviews r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.rating >= 4 
        ORDER BY r.created_at DESC 
        LIMIT 3";

$result = mysqli_query($conn, $sql);

$reviews = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $reviews[] = [
            'name' => htmlspecialchars($row['name']),
            'rating' => (int) $row['rating'],
            'comment' => htmlspecialchars($row['comment']),
            'date' => date('M d, Y', strtotime($row['created_at']))
        ];
    }
}

echo json_encode($reviews);
?>