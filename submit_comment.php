<?php
// submit_comment.php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $comment = htmlspecialchars($_POST['comment']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO comments (user_id, comment) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $comment);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Comment added successfully.";
    } else {
        $_SESSION['error'] = "Failed to add comment.";
    }
    header("Location: index.php#comments");
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>
