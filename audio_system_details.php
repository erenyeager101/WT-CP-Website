<?php
session_start(); // Start the session

// Define the price for each audio system
$audio_prices = [
    'Plasma' => 10000, // Price for Plasma
    'Top' => 5000    // Price for Top
];

// Handle form submission (optional, you can process this further based on your logic)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $plasma_quantity = $_POST['plasma_quantity'];
    $top_quantity = $_POST['top_quantity'];

    // Further processing logic (e.g., storing in session or database)
    $_SESSION['plasma_quantity'] = $plasma_quantity;
    $_SESSION['top_quantity'] = $top_quantity;

    header("Location: confirmation_page.php"); // Redirect to confirmation or another page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audio System Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <script>
        // Set default quantities for Plasma and Top systems
        let plasmaQuantity = 2;
        let topQuantity = 2;

        function updateTotalPrice() {
            // Prices of the audio systems
            let plasmaPrice = 10000;
            let topPrice = 5000;

            // Calculate the total price for both systems
            let totalPrice = (plasmaPrice * plasmaQuantity) + (topPrice * topQuantity);

            // Update the total price in the form
            document.getElementById('total_price').innerText = 'Estimated Total Price: ₹' + totalPrice;

            // Update hidden input values for form submission
            document.getElementById('plasma_quantity_hidden').value = plasmaQuantity;
            document.getElementById('top_quantity_hidden').value = topQuantity;
        }

        function adjustQuantity(system, increment) {
            if (system === 'plasma') {
                plasmaQuantity += increment;
                if (plasmaQuantity < 0) plasmaQuantity = 0;
                document.getElementById('plasma_quantity').innerText = plasmaQuantity;
            } else if (system === 'top') {
                topQuantity += increment;
                if (topQuantity < 0) topQuantity = 0;
                document.getElementById('top_quantity').innerText = topQuantity;
            }

            updateTotalPrice();
        }

        // Run this when the page loads to set default values
        window.onload = function() {
            document.getElementById('plasma_quantity').innerText = plasmaQuantity;
            document.getElementById('top_quantity').innerText = topQuantity;
            updateTotalPrice();
        }
    </script>
    <style>
        .header-section {
            position: relative;
            background: url('img/audioback.jpg');
            color: #fff;
            height: 500px;
            padding: 100px 0;
            text-align: center;
        }

        .header-section .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .header-section .header-content {
            position: relative;
            z-index: 2;
        }

        .header-section h1 {
            font-size: 3rem;
        }

        .header-section p {
            font-size: 1.5rem;
        }

        .header-section .btn {
            margin-top: 20px;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="index.php">
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
                        <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#about">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header Section for Audio System Options -->
    <header class="header-section">
        <div class="overlay"></div>
        <div class="container header-content">
            <h1 class="display-4">Audio System Options</h1>
            <p class="lead">Select from our premium audio systems to enhance your event experience.</p>
            <a href="booking.php" class="btn btn-primary btn-lg">Book Now</a>
        </div>
    </header>

    <!-- Audio System Details Section -->
    <div class="container mt-5">
        <h2 class="text-center">Select the quantity for each audio system you need.</h2>

        <form action="" method="POST" class="mt-4">
            <div class="row">
                <!-- Plasma Option -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Plasma System</h5>
                            <p class="card-text">Price: ₹10000 per unit</p>
                            <div class="d-flex align-items-center mb-3">
                                <button type="button" class="btn btn-secondary" onclick="adjustQuantity('plasma', -1)">-</button>
                                <span id="plasma_quantity" class="mx-3">2</span>
                                <button type="button" class="btn btn-secondary" onclick="adjustQuantity('plasma', 1)">+</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Option -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Top System</h5>
                            <p class="card-text">Price: ₹5000 per unit</p>
                            <div class="d-flex align-items-center mb-3">
                                <button type="button" class="btn btn-secondary" onclick="adjustQuantity('top', -1)">-</button>
                                <span id="top_quantity" class="mx-3">2</span>
                                <button type="button" class="btn btn-secondary" onclick="adjustQuantity('top', 1)">+</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estimated Total Price -->
            <div class="mt-3">
                <p id="total_price">Estimated Total Price: ₹0</p>
            </div>

            <!-- Hidden Inputs to Store Quantities -->
            <input type="hidden" name="plasma_quantity" id="plasma_quantity_hidden" value="2">
            <input type="hidden" name="top_quantity" id="top_quantity_hidden" value="2">

        </form>
    </div>
    <div class="container mt-3">
                        <!-- Button to redirect to Check Booking Date page -->
                        <a href="checkdj_booking.php" class="btn btn-info">Go to Check Booking Page</a>
    </div>
    <br>
    <footer id="contact" class="footer-section py-4 bg-dark text-white">
        <div class="container">
            <div class="row">
                <!-- Quick Links -->
                <div class="col-md-3 mb-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#home" class="text-white text-decoration-none">Home</a></li>
                        <li><a href="#services" class="text-white text-decoration-none">Services</a></li>
                        <li><a href="#gallery" class="text-white text-decoration-none">Gallery</a></li>
                        <li><a href="#about" class="text-white text-decoration-none">About Us</a></li>
                        <li><a href="#booking" class="text-white text-decoration-none">Booking</a></li>
                        <li><a href="#contact" class="text-white text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <!-- Contact Us -->
                <div class="col-md-3 mb-3">
                    <h5>Contact Us</h5>
                    <p>123 Luxe Street,<br>Dream City, DC 45678</p>
                    <p>Email: <a href="mailto:info@exploreocity.com" class="text-white text-decoration-none">info@exploreocity.com</a></p>


                    <p>Phone: <a href="tel:+1234567890" class="text-white text-decoration-none">+1 (234) 567-890</a></p>
                </div>
                <!-- Follow Us -->
                <div class="col-md-3 mb-3">
                    <h5>Follow Us</h5>
                    <div class="social-icons">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in fa-lg"></i></a>
                    </div>
                </div>
                <!-- Newsletter Subscription -->
                <div class="col-md-3 mb-3">
                    <h5>Newsletter</h5>
                    <form>
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="Your Email" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Subscribe</button>
                    </form>
                </div>
            </div>
            <hr class="bg-white">
            <p class="text-center mb-0">&copy; 2024 Shantai Banquet Hall and Lawn. All rights reserved.</p>
        </div>
    </footer>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
