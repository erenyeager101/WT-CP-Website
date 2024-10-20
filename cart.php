<?php
include 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// Fetch cart items from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT cart.id as cart_id, decoration_items.item_name, decoration_items.price, cart.quantity 
        FROM cart 
        JOIN decoration_items ON cart.item_id = decoration_items.id 
        WHERE cart.user_id = '$user_id'";
$result = $conn->query($sql);

$cart_items = [];
$total_price = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total_price += $row['price'] * $row['quantity'];
    }
}

// Remove item from cart
if (isset($_POST['remove_item'])) {
    $cart_id = $_POST['cart_id'];
    $sql = "DELETE FROM cart WHERE id='$cart_id' AND user_id='$user_id'";
    $conn->query($sql);
    header("Location: cart.php"); // Refresh the page to reflect changes
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
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
                        <a class="nav-link" href="decoration_items.php">Decoration Items</a>
                    </li>
                    
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h1 style="margin-top: 90px;">Your Cart</h1>
        <?php if (!empty($cart_items)): ?>
            <ul class="list-group mb-3">
                <?php foreach ($cart_items as $item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= $item['item_name']; ?> - Rs. <?= $item['price']; ?> x <?= $item['quantity']; ?>
                        <form method="post" action="">
                            <input type="hidden" name="cart_id" value="<?= $item['cart_id']; ?>">
                            <button type="submit" name="remove_item" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
            <p class="total-price"><strong>Total Price: Rs. <?= $total_price; ?></strong></p>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
        
        <!-- Link back to shopping -->
        <a href="index.php" class="btn btn-primary">Back to Shopping</a>

        <!-- Button to go to Invoice page -->
        <a href="invoice.php" class="btn btn-success">Generate Invoice</a> <!-- This is the link to the invoice page -->

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
