<?php
include 'db.php';
$id = $_GET['id'];
// Delete operation
$sql = "DELETE FROM employees WHERE id=$id";
if ($conn->query($sql) === TRUE) {
    header("Location: index.php");
    exit();
} else {
    echo "Error: " . $conn->error;
}
?> 