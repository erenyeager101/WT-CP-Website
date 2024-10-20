// Wait for the DOM to load
document.addEventListener('DOMContentLoaded', function() {
  
    // Initialize Swiper for Gallery Slider
    const swiper = new Swiper('.swiper-container', {
      // Optional parameters
      loop: true,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      // If we need pagination
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      // Navigation arrows
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
    });
  
    // Navbar background change on scroll
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', function() {
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
  
    // Multi-Step Booking Form
    const bookingForm = document.getElementById('bookingForm');
    const steps = document.querySelectorAll('.step');
    let currentStep = 0;
  
    function showStep(step) {
      steps.forEach((s, index) => {
        s.classList.add('d-none');
        if (index === step) {
          s.classList.remove('d-none');
        }
      });
    }
  
    showStep(currentStep);
  
    // Next Button
    document.querySelectorAll('.next-btn').forEach(button => {
      button.addEventListener('click', () => {
        // Simple validation can be added here
        if (currentStep < steps.length - 1) {
          currentStep++;
          showStep(currentStep);
        }
      });
    });
  
    // Back Button
    document.querySelectorAll('.back-btn').forEach(button => {
      button.addEventListener('click', () => {
        if (currentStep > 0) {
          currentStep--;
          showStep(currentStep);
        }
      });
    });
  
    // Form Submission
    bookingForm.addEventListener('submit', function(e) {
      e.preventDefault();
      currentStep++;
      showStep(currentStep);
      bookingForm.reset();
    });
  
  });
  // scripts.js
document.addEventListener('DOMContentLoaded', function () {
  const nextButtons = document.querySelectorAll('.next-btn');
  const backButtons = document.querySelectorAll('.back-btn');
  const steps = document.querySelectorAll('.step');

  nextButtons.forEach(button => {
      button.addEventListener('click', () => {
          const currentStep = button.closest('.step');
          const nextStep = currentStep.nextElementSibling;
          currentStep.classList.add('d-none');
          nextStep.classList.remove('d-none');
      });
  });

  backButtons.forEach(button => {
      button.addEventListener('click', () => {
          const currentStep = button.closest('.step');
          const prevStep = currentStep.previousElementSibling;
          currentStep.classList.add('d-none');
          prevStep.classList.remove('d-none');
      });
  });
});
$(document).ready(function () {
  // Page load animations
  $(".header-section").hide().fadeIn(1500);
  $(".services-section").hide().slideDown(1000);

  // Scroll-triggered animations
  $(window).on("scroll", function () {
      var scrollTop = $(window).scrollTop();
      var windowHeight = $(window).height();

      // Animate services section
      $(".services-section").each(function () {
          var sectionTop = $(this).offset().top;
          if (scrollTop + windowHeight >= sectionTop) {
              $(this).animate({ opacity: 1, top: 0 }, 1000);
          }
      });

      // Animate service cards
      $(".service-card").each(function (i) {
          var cardTop = $(this).offset().top;
          if (scrollTop + windowHeight >= cardTop) {
              $(this).delay(i * 200).animate({ opacity: 1, top: 0 }, 1000);
          }
      });

      // Animate About Us section
      $(".about-us-section").each(function () {
          var sectionTop = $(this).offset().top;
          if (scrollTop + windowHeight >= sectionTop) {
              $(this).animate({ opacity: 1, top: 0 }, 1000);
          }
      });
  });

  // Smooth scrolling for navigation links
  $("a.nav-link").on("click", function (event) {
      if (this.hash !== "") {
          event.preventDefault();
          var hash = this.hash;
          $("html, body").animate(
              { scrollTop: $(hash).offset().top },
              800
          );
      }
  });

  // Button hover animation
  $(".btn").hover(
      function () {
          $(this).animate({ paddingLeft: "20px", paddingRight: "20px" }, 200);
      },
      function () {
          $(this).animate({ paddingLeft: "10px", paddingRight: "10px" }, 200);
      }
  );
$("#bookingForm").on("submit", function(e) {
        e.preventDefault();  // Prevent form from submitting traditionally

        $.ajax({
            url: "submit_booking.php",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                let res = JSON.parse(response);
                if (res.status === "success") {
                    alert("Thank you! Your booking request has been submitted. We will contact you very soon.");
                    $(".step").addClass("d-none");
                    $(".step-confirmation").removeClass("d-none");
                } else {
                    alert("There was an issue with your booking. Please try again.");
                }
            },
            error: function() {
                alert("An error occurred. Please try again.");
            }
        });
    });

    // Step handling (for navigation through form)
    $(".next-btn").click(function() {
        $(this).closest(".step").addClass("d-none").next(".step").removeClass("d-none");
    });

    $(".back-btn").click(function() {
        $(this).closest(".step").addClass("d-none").prev(".step").removeClass("d-none");
    });
});
