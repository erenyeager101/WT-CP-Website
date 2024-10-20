<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = 'uploads\comments/gallery/';
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $fileName = basename($_FILES['images']['name'][$key]);
        $filePath = $uploadDir . $fileName;
        $fileType = pathinfo($filePath, PATHINFO_EXTENSION);

        if (in_array($fileType, $allowedTypes)) {
            // Move the file to the uploads directory
            if (move_uploaded_file($tmp_name, $filePath)) {
                // Insert the image path into the database
                $query = "INSERT INTO gallery (image_path, uploaded_by) VALUES (?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("si", $filePath, $_SESSION['user_id']);
                $stmt->execute();
            }
        }
    }

    header("Location: gallery.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1>Upload Images</h1>
    <form action="upload_image.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="images" class="form-label">Select Images</label>
            <input class="form-control" type="file" name="images[]" id="images" multiple>
        </div>
        <button type="submit" class="btn btn-success">Upload</button>
    </form>
</div>
<br><br>
    <div class="text-center">
        <?php if (isset($_SESSION['user_id'])) : ?>
            <a href="gallery.php" class="btn btn-primary">Back</a>

        <?php endif; ?>
</body>
</html>