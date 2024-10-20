<?php
// process_payment.php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['booking_id'])) {
    $payment_method = htmlspecialchars($_POST['payment_method']);
    $booking_id = $_SESSION['booking_id'];

    // Fetch booking amount
    $stmt = $conn->prepare("SELECT total_amount FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute(); $stmt->bind_result($amount); $stmt->fetch();

    // Simulate payment processing (In real scenarios, integrate with payment gateways)
    $payment_status = 'Completed'; // Assume payment is successful

    // Insert payment record
    $stmt = $conn->prepare("INSERT INTO payments (booking_id, payment_method, amount, payment_status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isds", $booking_id, $payment_method, $amount, $payment_status);
    if ($stmt->execute()) {
        // Update booking payment status
        $stmt = $conn->prepare("UPDATE bookings SET payment_status = 'Completed' WHERE id = ?");
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();

        // Clear booking session
        unset($_SESSION['booking_id']);

        // Redirect to confirmation
        header("Location: confirmation.php");
        exit();
    } else {
        $_SESSION['error'] = "Payment failed. Please try again.";
        header("Location: payment.php");
        exit();
    }
} else {
    header("Location: booking.php");
    exit();
}
?>
