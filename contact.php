<?php
// contact.php

// Enable error reporting for debugging (Remove or comment out in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Rest of your code...
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Shantai Banquet and Lawn</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Swiper CSS for Gallery Slider (if needed) -->
    <link href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="styles.css" rel="stylesheet">
    <style>
        .location-btn {
            display: inline-block;
            padding: 10px 25px;
            font-size: 1.2rem;
            font-weight: bold;
            color: #fff;
            background: linear-gradient(90deg, #007bff, #00c6ff);
            border: none;
            border-radius: 50px;
            text-align: center;
            transition: background 0.4s ease;
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
        }

        .location-btn:hover {
            background: linear-gradient(90deg, #0056b3, #00a2cc);
            box-shadow: 0 7px 20px rgba(0, 123, 255, 0.6);
            transform: scale(1.05);
        }

        .location-btn i {
            margin-right: 8px;
        }
        </style>
</head>

<body>
    <!-- Navigation Bar -->
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
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#about">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="booking.php">Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#contact">Contact</a>
                    </li>
                    <?php if (isset($_SESSION['user_id'])) { ?>
                        <?php if ($_SESSION['role'] == 'admin') { ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Admin
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                                    <li><a class="dropdown-item" href="admin_dashboard.php">Dashboard</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                                </ul>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
                            </li>
                        <?php } ?>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Header Section with Overlay -->
    <header id="contact-header" class="header-section position-relative">
        <div id="contactHeaderCarousel" class="carousel slide header-carousel" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- Header Image Slide -->
                <div class="carousel-item active">
                    <img src="img/garden11.jpg" class="d-block w-100 header-image" alt="Header Image" id="imgcontact">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="header__overlay" id="highoverlay">
                            <h1>Contact Us</h1>
                            <p>We are here to assist you with your queries and bookings.</p>
                            
                        </div>
                    </div>
                </div>
                <!-- You can add more carousel items if desired -->
            </div>
            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#contactHeaderCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#contactHeaderCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            <!-- Carousel Indicators -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#contactHeaderCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <!-- Add more indicators if more slides are added -->
            </div>
        </div>
    </header>

    <!-- Feedback Messages -->
    

    <!-- Contact Information Section -->
    <section class="contact-info-section py-5">
        <div class="container">
            <h2 class="text-center mb-4">Get in Touch</h2>
            <div class="row">
                <!-- Address -->
                <div class="col-md-4 mb-4">
                    <div class="d-flex align-items-start">
                        <div class="me-3">
                            <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h5>Address</h5>
                            <p>Shantai Lawns, Ahmed Nagar, Sangvi Dumala, Ahmadnagar SH-10, Ahmednagar - Daund Rd, Maharashtra 414701</p>
                        </div>
                    </div>
                </div>
                <!-- Email -->
                <div class="col-md-4 mb-4">
                    <div class="d-flex align-items-start">
                        <div class="me-3">
                            <i class="fas fa-envelope fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h5>Email</h5>
                            <p><a href="mailto:rohanalage@gmail.com" class="text-decoration-none text-dark">rohanalage@gmail.com</a></p>
                        </div>
                    </div>
                </div>
                <!-- Phone -->
                <div class="col-md-4 mb-4">
                    <div class="d-flex align-items-start">
                        <div class="me-3">
                            <i class="fas fa-phone fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h5>Phone</h5>
                            <p><a href="tel:+918484914041" class="text-decoration-none text-dark">+91 8484914041</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Embedded Google Map -->
    <section class="map-section py-5">
        <div class="container">
            <h2 class="text-center mb-4">Our Location</h2>
            <div class="ratio ratio-16x9">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.1234567890123!2d-122.419415084681!3d37.77492977975963!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085808a8b12345%3A0xabcdef1234567890!2sShantai%20Banquet%20and%20Lawn!5e0!3m2!1sen!2sus!4v1600000000000!5m2!1sen!2sus" width="300" height="250" style="border:0;" allowfullscreen="" loading="lazy" title="Shantai Banquet and Lawn Location"></iframe>
            </div>
        </div>
    </section>
    <div class="container text-center mt-5">
    <!-- Location Button -->
    <a href="https://maps.app.goo.gl/8Ru2E4M8VHiosR1r7" target="_blank" class="location-btn">
        <i class="fas fa-map-marker-alt"></i> Visit Our Location
    </a>
</div>

<br>

    <!-- Footer -->
    <footer id="contact-footer" class="footer-section py-4 bg-dark text-white">
        <div class="container">
            <div class="row">
                <!-- Quick Links -->
                <div class="col-md-3 mb-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php#home" class="text-white text-decoration-none">Home</a></li>
                        <li><a href="gallery.php" class="text-white text-decoration-none">Gallery</a></li>
                        <li><a href="index.php#about" class="text-white text-decoration-none">About Us</a></li>
                        <li><a href="booking.php" class="text-white text-decoration-none">Booking</a></li>
                        <li><a href="contact.php" class="text-white text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <!-- Contact Us -->
                <div class="col-md-3 mb-3">
                    <h5>Contact Us</h5>
                    <p>Shantai Lawns<br> Sangvi Dumala, Ahmadnagar SH-10<br> Ahmednagar - Daund Rd<br>Maharashtra 414701</p>
                    <p>Email: <a href="mailto:rohanalage@gmail.com" class="text-white text-decoration-none">rohanalage@gmail.com</a></p>
                    <p>Phone: <a href="tel:+918484914041" class="text-white text-decoration-none">+91 8484914041</a></p>
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

    <!-- Bootstrap JS and dependencies (Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Swiper JS for Gallery Slider (if needed) -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <!-- jQuery (Optional for Forms) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script src="scripts.js"></script>
    
</body>

</html>
