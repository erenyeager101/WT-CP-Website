<?php
// payment.php
session_start();
require 'config.php';

if (!isset($_SESSION['booking_id'])) {
    header("Location: booking.php");
    exit();
}

$booking_id = $_SESSION['booking_id'];

// Fetch booking details
$stmt = $conn->prepare("SELECT bookings.*, services.name AS service_name FROM bookings JOIN services ON bookings.service_id = services.id WHERE bookings.id = ?");
$stmt->bind_param("i", $booking_id);
$stmt->execute(); $result = $stmt->get_result();
$booking = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include Bootstrap CSS -->
    <title>Payment - Exploreocity</title>
</head>
<body>
<div class="container">
    <h2 class="mt-5">Payment for Your Booking</h2>
    <div class="card mt-3">
        <div class="card-body">
            <h5 class="card-title">Booking Details</h5>
            <p><strong>Service:</strong> <?php echo htmlspecialchars($booking['service_name']); ?></p>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($booking['event_date']); ?></p>
            <p><strong>Time:</strong> <?php echo htmlspecialchars($booking['event_time']); ?></p>
            <p><strong>Total Amount:</strong> $<?php echo htmlspecialchars($booking['total_amount']); ?></p>
        </div>
    </div>
    <form action="process_payment.php" method="POST" class="mt-4">
        <h4>Select Payment Method</h4>
        <div class="mb-3">
            <select class="form-select" name="payment_method" required>
                <option value="" selected disabled>Select a payment method</option>
                <option value="Credit Card">Credit Card</option>
                <option value="Debit Card">Debit Card</option>
                <option value="PayPal">PayPal</option>
                <option value="Bank Transfer">Bank Transfer</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Pay Now</button>
    </form>
</div>
<!-- Include Bootstrap JS -->
</body>
</html>
