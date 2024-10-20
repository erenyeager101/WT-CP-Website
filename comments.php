<?php
session_start();
$conn = new mysqli("localhost", "root", "ruturaj", "shantai");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("INSERT INTO comments (user_id, comment) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $comment);

    if ($stmt->execute()) {
        echo "Comment submitted!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
