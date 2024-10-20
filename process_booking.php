<?php
// process_booking.php
session_start();
require 'config.php';

// Fetch form data
$service_id = $_POST['service'];
$event_date = $_POST['event_date'];
$event_time = $_POST['event_time'];
$full_name = htmlspecialchars($_POST['full_name']);
$email = htmlspecialchars($_POST['email']);
$phone = htmlspecialchars($_POST['phone']);
$decoration = htmlspecialchars($_POST['decoration']);
$lighting = htmlspecialchars($_POST['lighting']);
$other_requests = htmlspecialchars($_POST['other_requests']);

// Check availability
$stmt = $conn->prepare("SELECT id FROM bookings WHERE service_id = ? AND event_date = ? LIMIT 1");
$stmt->bind_param("is", $service_id, $event_date);
$stmt->execute(); $stmt->store_result();
if ($stmt->num_rows > 0) {
    $_SESSION['error'] = "The selected service is not available on this date. Please choose another date.";
    header("Location: booking.php");
    exit();
}

// Fetch service charge
$stmt = $conn->prepare("SELECT fixed_charge FROM services WHERE id = ?");
$stmt->bind_param("i", $service_id);
$stmt->execute(); $stmt->bind_result($fixed_charge); $stmt->fetch();

// Calculate additional charges
$additional_charges = 0;
if ($decoration == 'Basic') $additional_charges += 5000;
elseif ($decoration == 'Premium') $additional_charges += 10000;

if ($lighting == 'Standard') $additional_charges += 3000;
elseif ($lighting == 'Advanced') $additional_charges += 7000;

// Total amount
$total_amount = $fixed_charge + $additional_charges;

// Insert booking
$stmt = $conn->prepare("INSERT INTO bookings (user_id, service_id, event_date, event_time, decoration, lighting, other_requests, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$user_id = $_SESSION['user_id'];
$stmt->bind_param("iisssssd", $user_id, $service_id, $event_date, $event_time, $decoration, $lighting, $other_requests, $total_amount);
if ($stmt->execute()) {
    $booking_id = $stmt->insert_id;
    $_SESSION['booking_id'] = $booking_id;
    header("Location: payment.php");
    exit();
} else {
    $_SESSION['error'] = "Failed to process booking. Please try again.";
    header("Location: booking.php");
    exit();
}
?>
