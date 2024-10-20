<?php
// index.php
session_start(); // Start the session
// Fetch comments
require 'config.php';
$comments_stmt = $conn->prepare("SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.id ORDER BY comments.created_at DESC");
$comments_stmt->execute();
$comments_result = $comments_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shantai - Book Your Perfect Venue</title>
    <!-- Bootstrap CSS -->
     
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Lora:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Swiper CSS for Gallery Slider -->
    <link href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="styles.css" rel="stylesheet">
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="fontstyle">
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
                        <a class="nav-link" href="#about">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="decoration_items.php">Shopping</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="booking.php">Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
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
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <i class="fas fa-shopping-cart"></i>Cart
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <!-- Header Section with Carousel (Video + 2 Images) -->
    <header id="home" class="header-section">
        <div id="headerCarousel" class="carousel slide header-carousel" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- Video Slide -->
                <div class="carousel-item active">
                    <video class="d-block w-100 header-video" autoplay muted loop>
                        <source src="img/header.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="header__overlay">
                            <h1>Your Perfect Venue for Unforgettable Moments</h1>
                            <p>Our banquet is a great place to host outdoor events</p>
                        </div>
                    </div>
                </div>
                <!-- Add more carousel items if needed -->
                <div class="carousel-item">
                    <img src="img/garden1.jpg" class="d-block w-100 header-image" alt="Header Image 2">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="header__overlay">
                            <h1>Your Perfect Venue for Unforgettable Moments</h1>
                            <p>Choose the best halls, lawns, and lodges for your events</p>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="img/image.jpg" class="d-block w-100 header-image" alt="Header Image 1">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="header__overlay">
                            <h1>Your Perfect Venue for Unforgettable Moments</h1>
                            <p>Choose the best halls, lawns, and lodges for your events</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#headerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#headerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            <!-- Carousel Indicators -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#headerCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#headerCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#headerCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
        </div>
    </header>

    <!-- Services Section with Interactive Cards -->
    <!-- Services Section with Interactive Cards -->
    <!-- Services Section with Interactive Cards -->
    <section id="services" class="services-section py-5">
        <div class="container text-center">
            <h2 class="mb-4">Our Services</h2>
            <div class="row d-flex justify-content-center">
                <!-- Banquet Hall Card -->
                <div class="col-md-4 mb-4">
                    <div class="card service-card">
                        <img src="img/banquet.jpg" class="card-img-top" alt="Banquet Hall">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Banquet Hall</h5>
                            <p class="card-text">Elegant, beautiful and spacious halls for grand occasions.</p>
                            <a href="details.php?id=banquet" class="location-btn mt-auto" id="btnalign">
                                 Explore More
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Lawn Card -->
                <div class="col-md-4 mb-4">
                    <div class="card service-card h-100">
                        <img src="img/lawn.jpg" class="card-img-top" alt="Lawn">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Lawn</h5>
                            <p class="card-text">Beautifully landscaped lawns for outdoor events.</p>
                            <a href="details.php?id=lawn" class="location-btn mt-auto" id="btnalign">
                                Explore More
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Audio System Card -->
                <div class="col-md-4 mb-4">
                    <div class="card service-card h-100">
                        <img src="img/audiosystem.jpg" class="card-img-top" alt="Audio System">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Audio System</h5>
                            <p class="card-text">Top-notch audio systems for your event.</p>
                            <a href="audio_system_details.php" class="location-btn mt-auto" id="btnalign">
                                Explore More
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-5">
            <p class="text-center mx-auto mb-5" style="max-width: 800px; text-align: justify; font-size: 1.2rem;">
                Our banquet is a great place to host outdoor events such as wedding receptions, corporate events, birthday parties, and more special occasions.<br>
                Our services include 1000-1200 chairs, filtered water jars, 30-35 mattresses, and four rooms for the bride and groom within the lease (Hall rent itself).<br>
                There is plenty of parking space available for your convenience.
            </p>
        </div>
    </section>
    <!-- About Us Section with Timeline -->
    <section id="about" class="about-us-section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">About Us</h2>
            <p class="text-center mx-auto mb-5" style="max-width: 800px;">
                At our Banquet, we blend luxury and comfort to provide the perfect venues for your special events.
                With state-of-the-art banquet halls, lush green lawns, and cozy lodges, we ensure an unforgettable experience
                for you and your guests. Our customizable packages and top-notch amenities cater to all your event needs.
            </p>
            <div class="timeline">
                <!-- Timeline Item 1 -->
                <div class="timeline-item">
                    <div class="timeline-icon"><i class="fas fa-calendar-alt"></i></div>
                    <div class="timeline-content">
                        <p>Make sure the date you want for your event is available.</p>
                    </div>
                </div>
                <!-- Timeline Item 2 -->
                <div class="timeline-item">
                    <div class="timeline-icon"><i class="fas fa-hotel"></i></div>
                    <div class="timeline-content">
                        <p>Decide on the venue area, whether it's a hall, lawn, or both.</p>
                    </div>
                </div>
                <!-- Timeline Item 3 -->
                <div class="timeline-item">
                    <div class="timeline-icon"><i class="fas fa-tree"></i></div>
                    <div class="timeline-content">
                        <p>Decide on the decoration you desire from the cards mentioned above.</p>
                    </div>
                </div>
                <!-- Timeline Item 4 -->
                <div class="timeline-item">
                    <div class="timeline-icon"><i class="fas fa-bed"></i></div>
                    <div class="timeline-content">
                        <p>If you have any additional decorating ideas, feel free to contact us.</p>
                    </div>
                </div>
                <!-- Timeline Item 5 -->
                <div class="timeline-item">
                    <div class="timeline-icon"><i class="fas fa-users"></i></div>
                    <div class="timeline-content">
                        <p>We also offer a complete package system, so please inform us of any additional features and facilities you require.</p>
                    </div>
                </div>
                <!-- Timeline Item 6 -->
                <div class="timeline-item">
                    <div class="timeline-icon"><i class="fas fa-award"></i></div>
                    <div class="timeline-content">
                        <p>Kindly get in touch with us to verify the date of the event.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Comments Section -->
<section id="comments" class="comments-section py-5 bg-secondary text-white">
    <div class="container">
        <h2 class="text-center mb-5">User Comments</h2>
        <div class="comments-list mb-5 d-flex flex-column align-items-center">
            <?php while ($comment = $comments_result->fetch_assoc()) { ?>
                <div class="card comment-card mb-4" style="max-width: 650px; width: 100%;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0"><?php echo htmlspecialchars($comment['username']); ?></h5>
                            <small class="text-muted"><?php echo htmlspecialchars($comment['created_at']); ?></small>
                        </div>
                        <p class="card-text"><?php echo htmlspecialchars($comment['comment']); ?></p>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php if (isset($_SESSION['user_id'])) { ?>
            <form action="submit_comment.php" method="POST" class="comment-form">
                <div class="mb-3">
                    <textarea class="form-control" name="comment" rows="3" placeholder="Leave a comment..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Submit Comment</button>
            </form>
        <?php } else { ?>
            <p class="text-center" id="color">Please <a href="login.php" class="text-blue fw-bold">login</a> to leave a comment.</p>
        <?php } ?>
    </div>
</section>


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
    <!-- Swiper JS for Gallery Slider -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
    <!-- jQuery (Optional for Booking Form) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Custom JS -->
    <script src="scripts.js"></script>
</body>

</html>