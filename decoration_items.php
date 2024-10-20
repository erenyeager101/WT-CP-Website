<?php
// config.php
session_start();
include 'config.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// Fetch decoration items from the database
$sql = "SELECT id, item_name, price, image_url FROM decoration_items";
$result = $conn->query($sql);

$items = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}

// Initialize added_to_cart variable
$added_to_cart = false;

// Add item to the cart
if (isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session
    $quantity = 1; // Default quantity

    $sql = "INSERT INTO cart (user_id, item_id, quantity) VALUES ('$user_id', '$item_id', '$quantity')";
    if ($conn->query($sql) === TRUE) {
        $added_to_cart = true; // Set variable to true if insertion was successful
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shantai Lawns - Marriage Hall Decorations</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Arial', sans-serif;
        }
        .page-title {
            color: #2c3e50;
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .decoration-item {
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .decoration-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .decoration-item img {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            object-fit: cover;
            height: 350px;
            width: 100%;
        }
        .decoration-item .card-body {
            padding: 20px;
        }
        .decoration-item .card-title {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .decoration-item .card-text {
            color: #7f8c8d;
            font-size: 16px;
            margin-bottom: 15px;
        }
        .decoration-item .btn-success {
            background-color: #2ecc71;
            border: none;
            transition: all 0.3s ease;
            padding: 10px 20px;
            font-size: 16px;
        }
        .decoration-item .btn-success:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
        }
        .cart-section {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            margin-top: 40px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .cart-section h2 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 20px;
        }
        .cart-item {
            background-color: #f8f9fa;
            border: none;
            margin-bottom: 10px;
        }
        .btn-invoice {
            background-color: #3498db;
            border: none;
            transition: all 0.3s ease;
            padding: 12px 24px;
            font-size: 18px;
            margin-top: 20px;
        }
        .btn-invoice:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }

    </style>
</head>
<body>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="#">
                Shantai Banquet and Lawn
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Welcome Message (Visible Only When Logged In) -->
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <span class="navbar-text me-auto text-light">
                        Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
                    </span>
                <?php } ?>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="index.php#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#about">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="booking.php">Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <i class="fas fa-shopping-cart"></i>Cart
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

<div class="container mt-5">
    <h1 style="margin-top: 90px;" class="text-center page-title">Shantai Lawns Decoration Items</h1>
    <div class="row" id="items-container">
        <?php foreach ($items as $item): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 decoration-item">
                    <img src="<?= $item['image_url']; ?>" class="card-img-top" alt="<?= $item['item_name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $item['item_name']; ?></h5>
                        <p class="card-text"><strong>Price:</strong> Rs. <?= $item['price']; ?></p>
                        <form method="post" action="">
                            <input type="hidden" name="item_id" value="<?= $item['id']; ?>">
                            <button type="submit" name="add_to_cart" class="btn btn-success">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal for Item Added Notification -->
<div class="modal fade" id="itemAddedModal" tabindex="-1" aria-labelledby="itemAddedModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemAddedModalLabel">Item Added to Cart</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                The item has been successfully added to your cart!
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // Show the modal if the item has been added to the cart
        <?php if ($added_to_cart): ?>
            $('#itemAddedModal').modal('show');
        <?php endif; ?>
    });
</script>
</body>
</html>
