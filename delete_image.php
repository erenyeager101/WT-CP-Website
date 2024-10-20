<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: gallery.php");
    exit();
}

if (isset($_POST['image_id'])) {
    $imageId = intval($_POST['image_id']);

    // Fetch image path from database
    $query = "SELECT image_path FROM gallery WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $imageId);
    $stmt->execute();
    $result = $stmt->get_result();
    $image = $result->fetch_assoc();

    if ($image) {
        // Delete the file from the server
        if (file_exists($image['image_path'])) {
            unlink($image['image_path']);
        }

        // Delete the record from the database
        $deleteQuery = "DELETE FROM gallery WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("i", $imageId);
        $deleteStmt->execute();

        // Redirect to gallery
        header("Location: gallery.php");
        exit();
    } else {
        echo "Image not found.";
    }
}
?>