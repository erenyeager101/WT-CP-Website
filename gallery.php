<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

// Pagination
$imagesPerPage = 15;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $imagesPerPage;

// Fetch total number of images
$totalQuery = "SELECT COUNT(*) as total FROM gallery";
$totalResult = $conn->query($totalQuery);
$totalImages = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalImages / $imagesPerPage);

// Fetch images for current page
$query = "SELECT * FROM gallery LIMIT $offset, $imagesPerPage";
$result = $conn->query($query);

$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enchanting Photo Gallery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .gallery-container {
            margin-top: 50px;
        }
        .gallery-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 15px;
            transition: all 0.3s ease;
        }
        .gallery-image:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .admin-controls {
            text-align: center;
            margin-top: 10px;
        }
        .admin-controls button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        .admin-controls button:hover {
            background-color: #c0392b;
        }
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        .btn-custom {
            background-color: #3498db;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        .top-buttons {
            position: absolute;
            right: 20px;
            top: 20px;
        }
        .page-link {
            color: #3498db;
        }
        .page-item.active .page-link {
            background-color: #3498db;
            border-color: #3498db;
        }
        h1 {
            color: #2c3e50;
            font-weight: bold;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>

<div class="container gallery-container">
    <div class="top-buttons">
        <?php if (isset($_SESSION['user_id'])) : ?>
            <a href="upload_image.php" class="btn btn-custom me-2"><i class="fas fa-upload"></i> Add Photos</a>
            <a href="index.php" class="btn btn-custom"><i class="fas fa-home"></i> Back</a>
        <?php endif; ?>
    </div>
    <h1 class="text-center"><i class="fas fa-images"></i> Enchanting Photo Gallery</h1>
    <div class="row">
        <?php if ($result->num_rows > 0) : ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <a href="<?php echo $row['image_path']; ?>" data-lightbox="gallery" data-title="Click the right half of the image to move forward.">
                            <img src="<?php echo $row['image_path']; ?>" class="gallery-image" alt="Gallery Image">
                        </a>
                        <?php if ($isAdmin): ?>
                            <div class="admin-controls">
                                <form action="delete_image.php" method="post">
                                    <input type="hidden" name="image_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this image?')">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <p class="text-center">No images found in the gallery.</p>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script>
    lightbox.option({
        'resizeDuration': 200,
        'wrapAround': true,
        'albumLabel': "Image %1 of %2"
    });
</script>

</body>
</html>