<?php
include 'config.php';

$message = '';
$dateAvailable = true;
$date = '';
$bookedDates = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['date'])) {
    $date = $_POST['date'];
    
    // Check if the DJ is already booked on the selected date
    $stmt = $conn->prepare("SELECT * FROM dj_bookings WHERE booked_date = ?");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $message = "Sorry, the DJ is already booked on this date.";
        $dateAvailable = false;
    } else {
        $message = "The DJ is available for booking on this date!";
    }
    $stmt->close();
}

// Fetch all DJ booked dates
$sql = "SELECT * FROM dj_bookings ORDER BY booked_date";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $bookedDates[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check DJ Booking Availability</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            max-width: 500px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #3a3a3a;
            margin-bottom: 30px;
            text-align: center;
            font-weight: 600;
        }
        .form-control {
            border: none;
            border-bottom: 2px solid #007bff;
            border-radius: 0;
            padding: 10px 5px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #0056b3;
        }
        .btn {
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            transform: translateY(-2px);
        }
        .result-message {
            margin-top: 20px;
            padding: 15px;
            border-radius: 10px;
            font-weight: 500;
            text-align: center;
        }
        .available {
            background-color: #d4edda;
            color: #155724;
        }
        .not-available {
            background-color: #f8d7da;
            color: #721c24;
        }
        .modal-content {
            border-radius: 15px;
        }
        .modal-header {
            background-color: #007bff;
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .modal-body {
            max-height: 400px;
            overflow-y: auto;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 123, 255, 0.05);
        }
        .calendar-icon {
            position: absolute;
            right: 10px;
            top: 10px;
            color: #007bff;
        }
    </style>
</head>
<body>
<div class="container">
    <h2><i class="fas fa-calendar-check mr-2"></i>Check DJ Booking Availability</h2>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group position-relative">
            <label for="date">Select a Date:</label>
            <input type="date" class="form-control" id="date" name="date" required>
            <i class="fas fa-calendar calendar-icon"></i>
        </div>
        <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-2"></i>Check Availability</button>
    </form>
    
    <?php if ($message): ?>
        <div class="result-message <?php echo $dateAvailable ? 'available' : 'not-available'; ?>">
            <i class="<?php echo $dateAvailable ? 'fas fa-check-circle' : 'fas fa-times-circle'; ?> mr-2"></i>
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <button type="button" class="btn btn-info btn-block mt-3" data-toggle="modal" data-target="#bookedDatesModal">
        <i class="fas fa-list mr-2"></i>See All DJ Booked Dates
    </button>
    
    <a href="index.php" class="btn btn-secondary btn-block mt-3">
        <i class="fas fa-arrow-left mr-2"></i>Back to Home
    </a>
</div>

<!-- Modal for Booked Dates -->
<div class="modal fade" id="bookedDatesModal" tabindex="-1" role="dialog" aria-labelledby="bookedDatesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookedDatesModalLabel">All DJ Booked Dates</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Customer Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookedDates as $bookedDate): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($bookedDate['booked_date']); ?></td>
                            <td><?php echo htmlspecialchars($bookedDate['customer_name']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Set minimum date to today
    document.getElementById('date').min = new Date().toISOString().split("T")[0];
</script>
</body>
</html>