<?php
// details.php

// Function to sanitize input
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Get the 'id' parameter from the URL
$service_id = isset($_GET['id']) ? sanitize($_GET['id']) : '';

// Read the JSON file
$json_data = file_get_contents('services.json');
if ($json_data === false) {
    die('Error: Unable to read services data.');
}

// Decode JSON data
$services = json_decode($json_data, true);
if ($services === null) {
    die('Error: Invalid JSON data.');
}

// Find the service with the matching id
$service = null;
foreach ($services['services'] as $item) {
    if ($item['id'] === $service_id) {
        $service = $item;
        break;
    }
}

// If service not found, display an error message
if ($service === null) {
    die('Error: Service not found.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Exploreocity - <?php echo $service['name']; ?> Details</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome for Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <!-- Swiper CSS for Gallery Slider -->
  <link href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="styles.css" rel="stylesheet">
  <style>
    /* Custom styles for the detail page */
    body {
      padding-top: 70px; /* To prevent content from being hidden behind the fixed navbar */
    }
    .header-section {
      position: relative;
      height: 60vh;
      background: url('<?php echo $service['headerImage']; ?>') no-repeat center center/cover;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      text-align: center;
    }
    .header-section .overlay {
      position: absolute;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1;
    }
    .header-content {
      position: relative;
      z-index: 2;
      max-width: 800px;
    }
    .specifications, .features, .decoration-items {
      padding: 60px 0;
    }
    .specifications ul {
      list-style: none;
      padding: 0;
    }
    .specifications ul li {
      padding: 10px 0;
      border-bottom: 1px solid #eaeaea;
    }
    .specifications ul li:last-child {
      border-bottom: none;
    }
    .specifications img {
      width: 100%;
      border-radius: 8px;
      margin-top: 20px;
    }
    .feature-item {
      text-align: center;
      padding: 20px;
      transition: transform 0.3s;
    }
    .feature-item:hover {
      transform: translateY(-10px);
    }
    .feature-item img {
      max-width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 15px;
    }
    .gallery-section {
      padding: 60px 0;
      background-color: #f9f9f9;
    }
    .swiper-container {
      width: 100%;
      padding-top: 20px;
      padding-bottom: 40px;
    }
    .swiper-slide {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .swiper-slide img {
      width: 100%;
      height: auto;
      object-fit: cover;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      cursor: pointer;
    }
    .call-to-action {
      padding: 60px 0;
      background-color: #007bff;
      color: black;
      text-align: center;
    }
    .call-to-action a {
      color: black;
    }
    .footer-section {
      padding: 40px 0;
      background-color: #343a40;
      color: #fff;
    }
    .footer-section a {
      color: #fff;
    }
    @media (max-width: 767.98px) {
      .header-section {
        height: 50vh;
      }
      .feature-item img {
        height: 150px;
      }
    }
    /* Custom styles for locking scroll */
    body.lock-scroll {
      overflow: hidden;
    }
    .swiper-container.lock-scroll {
      overflow: scroll; /* Allow scrolling for swiper */
    }
    
    .card-img-top{
      height: 400px;
    }
    .card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-10px) scale(1.05);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .card:hover .card-img-top {
      transform: scale(1.05); /* Slightly enlarge the image */
    }

    /* Adjusted image height for decoration items */
    .card-img-top {
      height: 300px; /* Adjusted height */
      object-fit: cover; /* Ensures the image covers the entire area without distortion */
      transition: transform 0.3s ease; /* Maintains the existing transition */
    }

    /* Set a fixed height for decoration item cards */
    .card.decoration-item {
      height: 500px; /* Adjust to desired height */
      display: flex;
      flex-direction: column;
    }

    /* Ensure the card body expands to fill the remaining space */
    .card.decoration-item .card-body {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    /* Responsive adjustments */
    @media (max-width: 767.98px) {
      .card.decoration-item {
        height: 400px; /* Reduced height for smaller screens */
      }
      .card-img-top {
        height: 200px; /* Reduced image height for smaller screens */
      }
    }
    #highimg{
      width: 400px;
      height: 300px;
    }
    #heights{
      margin-top: 0px;
    }
    #buttons{
      background-color: #74C365;
      border-color: #74C365;
    }
    #buttons:hover{
      background-color: #ACE1AF;
    }
  </style>
</head>
<body>

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="index.php" id="logocolor">Shantai Banquet and Lawn</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDetail" 
              aria-controls="navbarNavDetail" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNavDetail">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="index.php#services">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="index.php#about">About Us</a></li>
          <li class="nav-item"><a class="nav-link" href="decoration_items.php">Decoration Items</a></li>
          <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link active" href="#"><?php echo $service['name']; ?></a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Header Section -->
  <header class="header-section">
    <div class="overlay"></div>
    <div class="container header-content">
      <h1 class="display-4"><?php echo $service['name']; ?></h1>
      <p class="lead"><?php echo $service['description']; ?></p>
      <a href="booking.php" class="btn btn-primary btn-lg">Book Now</a>
    </div>
  </header>

  <!-- Specifications Section -->
  <section class="specifications py-5" id="heights">
    <div class="container">
      <h2 class="text-center mb-5">Specifications</h2>
      <div class="row align-items-center">
        <div class="col-md-6">
          <ul>
            <?php foreach ($service['specifications'] as $key => $value): ?>
              <li><strong><?php echo $key; ?>:</strong> <?php echo $value; ?></li>
            <?php endforeach; ?>
          </ul>
          <div class="container mt-3">
                        <!-- Button to redirect to Check Booking Date page -->
                        <a href="check_booking.php" class="btn btn-info">Go to Check Booking Page</a>
    </div>
        </div>
        <div class="col-md-6">
          <img src="<?php echo $service['headerImage']; ?>" alt="<?php echo $service['name']; ?> Specifications" class="img-fluid rounded shadow">
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="features py-5">
    <div class="container">
      <h2 class="text-center mb-5">Our Features</h2>
      <div class="row">
        <?php foreach ($service['features'] as $feature): ?>
          <div class="col-md-4 mb-4">
            <div class="feature-item h-100">
              <img src="<?php echo $feature['image']; ?>" alt="<?php echo $feature['title']; ?>" id="highimg">
              <h5 class="mt-3"><?php echo $feature['title']; ?></h5>
              <p><?php echo $feature['description']; ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

  <!-- Footer Section -->
  <footer class="footer-section text-center">
    <div class="container">
      <p>&copy; 2024 Shantai Banquet Hall And Lawn. All rights reserved.</p>
      <p><a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
    </div>
  </footer>

  <!-- Image Modal -->
  <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-body text-center">
          <img src="" id="modalImage" alt="Gallery Image" class="img-fluid">
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS, Popper.js, and jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <!-- Swiper JS -->
  <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
  
  <!-- Custom JS -->
  <script>

    // Initialize the page
    document.addEventListener('DOMContentLoaded', function () {
      renderItems();
      setupPagination();
    });



    // Setup pagination buttons
    function setupPagination() {
      const paginationContainer = document.getElementById('pagination');
      const totalPages = Math.ceil(decorationItems.length / itemsPerPage);
      paginationContainer.innerHTML = '';

      for (let i = 1; i <= totalPages; i++) {
        paginationContainer.innerHTML += `
          <li class="page-item ${i === currentPage ? 'active' : ''}">
            <a class="page-link" href="#" onclick="goToPage(${i})">${i}</a>
          </li>
        `;
      }
    }

    // Go to a specific page
    function goToPage(page) {
      currentPage = page;
      renderItems();
      setupPagination();
    }

    // Add search functionality
    document.getElementById('searchInput').addEventListener('input', function () {
      currentPage = 1; // Reset to first page on search
      renderItems();
      setupPagination();
    });

    const swiper = new Swiper('.swiper-container', {
      loop: true,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      spaceBetween: 30,
      centeredSlides: true,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });

    // Handle gallery image click to open modal
    const galleryImages = document.querySelectorAll('.swiper-slide img');
    const modalImage = document.getElementById('modalImage');

    galleryImages.forEach(image => {
      image.addEventListener('click', function() {
        const imageUrl = this.src;
        modalImage.src = imageUrl;
        // Lock scroll when modal is open
        document.body.classList.add('lock-scroll');
        // Show the modal
        $('#imageModal').modal('show');
      });
    });

    // Close modal and unlock scroll
    const modal = document.getElementById('imageModal');
    modal.addEventListener('hidden.bs.modal', function () {
      document.body.classList.remove('lock-scroll'); // Reset overflow style
    });
  </script>
</body>
</html>
