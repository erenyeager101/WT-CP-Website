<?php
// update_booking.php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
require 'config.php';

if (isset($_GET['id'])) {
    $booking_id = intval($_GET['id']);

    // Update payment status
    $stmt = $conn->prepare("UPDATE bookings SET payment_status = 'Completed' WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    if ($stmt->execute()) {
        $_SESSION['success'] = "Booking marked as paid.";
    } else {
        $_SESSION['error'] = "Failed to update booking.";
    }
    header("Location: admin_dashboard.php");
    exit();
} else {
    header("Location: admin_dashboard.php");
    exit();
}
?>
