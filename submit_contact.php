<?php
// submit_contact.php
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validate form data
    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        // Option 1: Send an email
        $to = "rawaterutu611@gmail.com"; // Replace with your email address
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $full_message = "You have received a new message from $name ($email):\n\n$message";

        if (mail($to, $subject, $full_message, $headers)) {
            // Optionally, send a confirmation email to the user
            $confirmation_subject = "Thank you for contacting Shantai Banquet and Lawn";
            $confirmation_message = "Dear $name,\n\nThank you for reaching out to us. We have received your message and will get back to you shortly.\n\nBest regards,\nShantai Banquet and Lawn Team";
            $confirmation_headers = "From: rawaterutu611@gmail.com\r\n";

            mail($email, $confirmation_subject, $confirmation_message, $confirmation_headers);

            // Redirect with success message
            header("Location: contact.php?status=success");
            exit();
        } else {
            // Redirect with error message
            header("Location: contact.php?status=error");
            exit();
        }

        // Option 2: Store in database (if needed)
        /*
        require 'config.php'; // Include your database configuration

        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);

        if ($stmt->execute()) {
            header("Location: contact.php?status=success");
            exit();
        } else {
            header("Location: contact.php?status=error");
            exit();
        }
        */
    } else {
        // Redirect with error message if fields are empty
        header("Location: contact.php?status=error");
        exit();
    }
} else {
    // Redirect to contact page if accessed directly
    header("Location: contact.php");
    exit();
}
?>
