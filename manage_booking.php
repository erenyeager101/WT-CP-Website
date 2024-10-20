<?php
session_start();
require 'config.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    echo json_encode(['success' => false, 'message' => 'Access denied. Admins only.']);
    exit();
}

// Handle deletion of a booking
if (isset($_GET['delete'])) {
    $booking_id = $_GET['delete'];

    // Prepare and execute delete query
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Booking deleted successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error deleting booking: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Fetch all bookings
$result = $conn->query("SELECT id, service, name, email, phone, date FROM bookings ORDER BY created_at DESC");

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shantai - Manage Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #333;
            font-family: 'Arial', sans-serif;
        }
        .manage-container {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 50px;
            margin-bottom: 50px;
        }
        h1 {
            color: #74C365;
            margin-bottom: 30px;
            text-align: center;
        }
        .table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .table thead {
            background-color: #74C365;
            color: white;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-action {
            padding: 5px 10px;
            font-size: 0.9rem;
            margin: 2px;
        }
    </style>
</head>
<body>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 manage-container">
                
                <h1><i class="fas fa-tasks me-2"></i><br> <br>Manage Bookings</h1>

                <?php if ($result->num_rows > 0): ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Serial No.</th>
                            <th>Name</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $serial_no = 1; 
                        while ($booking = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $serial_no++; ?></td>
                                <td><?php echo htmlspecialchars($booking['name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['service']); ?></td>
                                <td><?php echo htmlspecialchars($booking['date']); ?></td>
                                <td><?php echo htmlspecialchars($booking['email']); ?></td>
                                <td><?php echo htmlspecialchars($booking['phone']); ?></td>
                                <td>
                                    <a href="?delete=<?php echo $booking['id']; ?>" class="btn btn-danger btn-action" title="Delete" onclick="return confirm('Are you sure you want to delete this booking?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <p class="text-center">No bookings found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="text-center">
        <?php if (isset($_SESSION['user_id'])) : ?>
            <a href="admin_dashboard.php" class="btn btn-primary">Back</a>

        <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>