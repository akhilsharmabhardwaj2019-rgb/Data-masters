<?php
session_start();
include '../db.php';

$result = mysqli_query($conn,
    "SELECT problems.*, users.name 
     FROM problems JOIN users ON problems.user_id = users.id");
?>

<h2>Customer Problems</h2>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
    <p>
        <b><?php echo $row['name']; ?></b><br>
        Problem: <?php echo $row['problem']; ?><br>
        Status: <?php echo $row['status']; ?>
    </p>

    <form action="update_solution.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <textarea name="solution" placeholder="Enter solution"></textarea>
        <button type="submit">Send Solution</button>
    </form>
    <hr>
<?php } ?>
