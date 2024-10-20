<?php
// admin_dashboard.php

include 'config.php';


// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Handle Booking Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['submit'])) {
        $customer_name = $_POST['customer_name'] ?? null;
        $customer_mobile = $_POST['customer_mobile'] ?? null;
        $booked_date = $_POST['booked_date'] ?? null;
        $edit_id = $_POST['edit_id'] ?? null;
        $booking_type = $_POST['booking_type'] ?? 'banquet';

        if ($customer_name && $customer_mobile && $booked_date) {
            // Determine table to work with (banquet or DJ)
            $table_name = ($booking_type == 'dj') ? 'dj_bookings' : 'booked_dates';

            // Check if the date is already booked
            $check_date = $conn->prepare("SELECT * FROM $table_name WHERE booked_date = ? AND id != ?");
            $check_date->bind_param("si", $booked_date, $edit_id);
            $check_date->execute();
            $result = $check_date->get_result();

            if ($result->num_rows > 0) {
                $message = "This date is already booked. Please choose another date.";
                $alert_class = "danger";
            } else {
                if ($edit_id) {
                    $stmt = $conn->prepare("UPDATE $table_name SET customer_name = ?, customer_mobile = ?, booked_date = ? WHERE id = ?");
                    $stmt->bind_param("sssi", $customer_name, $customer_mobile, $booked_date, $edit_id);
                    $action = "updated";
                } else {
                    $stmt = $conn->prepare("INSERT INTO $table_name (customer_name, customer_mobile, booked_date) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $customer_name, $customer_mobile, $booked_date);
                    $action = "booked";
                }

                if ($stmt->execute()) {
                    $message = ucfirst($booking_type) . " date $action successfully!";
                    $alert_class = "success";
                } else {
                    $message = "Error: " . $stmt->error;
                    $alert_class = "danger";
                }
                $stmt->close();
            }
        } else {
            $message = "Please fill out all fields.";
            $alert_class = "danger";
        }
        
        // Redirect to prevent form resubmission
        header("Location: admin_dashboard.php?message=" . urlencode($message) . "&alert=" . $alert_class);
        exit();
    }
}

// Handle Delete Request
if (isset($_GET['delete']) && isset($_GET['type'])) {
    $delete_id = $_GET['delete'];
    $type = $_GET['type'];
    $table_name = ($type == 'dj') ? 'dj_bookings' : 'booked_dates';
    
    $conn->query("DELETE FROM $table_name WHERE id=$delete_id");
    $message = ucfirst($type) . " date deleted successfully!";
    $alert_class = "success";
    header("Location: admin_dashboard.php?message=" . urlencode($message) . "&alert=" . $alert_class);
    exit();
}

// Fetch all booked banquet dates
$banquet_result = $conn->query("SELECT * FROM booked_dates ORDER BY booked_date");
// Fetch all booked DJ dates
$dj_result = $conn->query("SELECT * FROM dj_bookings ORDER BY booked_date");

    // Handle Decoration Item Addition
    


// Fetch all booked dates to display in the table
$result = $conn->query("SELECT * FROM booked_dates ORDER BY booked_date");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Exploreocity</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
            margin-top: 50px;
            margin-bottom: 50px;
        }
        h2, h3 {
            color: #007bff;
            margin-bottom: 30px;
        }
        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }
        .btn-warning:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .table thead th {
            background-color: #007bff;
            color: white;
            border: none;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0,123,255,.05);
        }
    </style>
</head>
<body>
    <!-- Start of Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="admin_dashboard.php"><i class="fas fa-calendar-alt mr-2"></i>Shantai Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdownAdmin" aria-controls="navbarNavDropdownAdmin" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdownAdmin">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php"><i class="fas fa-home mr-1"></i>Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gallery.php"><i class="fas fa-book mr-1"></i>Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_cart_edit.php"><i class="fas fa-book mr-1"></i>Manage Items</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="manage_booking.php"><i class="fas fa-book mr-1"></i>Manage Booking</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="admin_list.php"><i class="fas fa-users-cog mr-1"></i>Manage Admin</a> <!-- New link to admin list page -->
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLinkAdmin" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user-cog mr-1"></i>Admin Options
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLinkAdmin">
                        <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End of Navbar -->

    <div class="container">
        <h2 class="text-center"><i class="fas fa-calendar-alt mr-2"></i>Manage Booked Dates</h2>

        <?php
        // Display messages related to bookings
        if (isset($_GET['message'])) {
            echo '<div class="alert alert-' . htmlspecialchars($_GET['alert']) . ' alert-dismissible fade show" role="alert">
                    ' . htmlspecialchars($_GET['message']) . '
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        }

        // Display messages related to decoration items
        if (isset($_GET['decoration_message'])) {
            echo '<div class="alert alert-' . htmlspecialchars($_GET['decoration_alert']) . ' alert-dismissible fade show" role="alert">
                    ' . htmlspecialchars($_GET['decoration_message']) . '
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
        }
        ?>

        <!-- Booking Form -->
        <form action="admin_dashboard.php" method="POST" class="mb-4">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="customer_name"><i class="fas fa-user mr-2"></i>Customer Name</label>
                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
            </div>
            <div class="form-group col-md-4">
                <label for="customer_mobile"><i class="fas fa-phone mr-2"></i>Customer Mobile</label>
                <input type="tel" class="form-control" id="customer_mobile" name="customer_mobile" required>
            </div>
            <div class="form-group col-md-4">
                <label for="booked_date"><i class="fas fa-calendar mr-2"></i>Booked Date</label>
                <input type="date" class="form-control" id="booked_date" name="booked_date" required>
            </div>
        </div>
        <div class="form-group">
            <label for="booking_type"><i class="fas fa-book mr-2"></i>Booking Type</label>
            <select id="booking_type" name="booking_type" class="form-control">
                <option value="banquet">Banquet</option>
                <option value="dj">DJ</option>
            </select>
        </div>
        <input type="hidden" name="edit_id" id="edit_id">
        <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i>Add/Update Date</button>
    </form>

        <!-- Booked Dates Table -->
        <h3 class="text-center mb-4">Existing Booked Dates</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Customer Mobile</th>
                    <th>Booked Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['customer_mobile']); ?></td>
                        <td><?php echo htmlspecialchars($row['booked_date']); ?></td>
                        <td>
                            <a href="javascript:void(0);" onclick="editDate(<?php echo $row['id']; ?>, '<?php echo addslashes($row['customer_name']); ?>', '<?php echo addslashes($row['customer_mobile']); ?>', '<?php echo $row['booked_date']; ?>')" class="btn btn-warning btn-sm mr-2"><i class="fas fa-edit"></i> Edit</a>
                            <a href="admin_dashboard.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this date?');"><i class="fas fa-trash-alt"></i> Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h3 class="text-center mt-5">DJ Bookings</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Customer Mobile</th>
                <th>Booked Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $dj_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['customer_mobile']); ?></td>
                    <td><?php echo htmlspecialchars($row['booked_date']); ?></td>
                    <td>
                        <a href="javascript:void(0);" onclick="editDate(<?php echo $row['id']; ?>, '<?php echo $row['customer_name']; ?>', '<?php echo $row['customer_mobile']; ?>', '<?php echo $row['booked_date']; ?>')" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                        <a href="admin_dashboard.php?delete=<?php echo $row['id']; ?>&type=dj" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this DJ booking?');"><i class="fas fa-trash-alt"></i> Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>


    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function editDate(id, name, mobile, date) {
        document.getElementById("customer_name").value = name;
        document.getElementById("customer_mobile").value = mobile;
        document.getElementById("booked_date").value = date;
        document.getElementById("edit_id").value = id;
    }
    </script>
</body>
</html>
