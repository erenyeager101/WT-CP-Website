<?php
// admin_cart_edit.php

include 'config.php'; // Assuming config.php contains your database connection setup

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Handle Add Decoration Item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_decoration_item'])) {
    $item_name = sanitize_input($_POST['item_name']);
    $item_price = sanitize_input($_POST['item_price']);

    // Handle file upload
    if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] == 0) {
        $image = $_FILES['item_image'];
        $target_dir = "img/decorations/"; // Specify your uploads directory
        $target_file = $target_dir . basename($image["name"]);

        // Check if the file is an actual image
        $check = getimagesize($image["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($image["tmp_name"], $target_file)) {
                // Insert the new decoration item into the database
                $stmt = $conn->prepare("INSERT INTO decoration_items (item_name, price, image_url) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $item_name, $item_price, $target_file);

                if ($stmt->execute()) {
                    $message = "Decoration item added successfully!";
                    $alert_class = "success";
                } else {
                    $message = "Error adding item: " . $stmt->error;
                    $alert_class = "danger";
                }
                $stmt->close();
            } else {
                $message = "Error uploading the image.";
                $alert_class = "danger";
            }
        } else {
            $message = "File is not an image.";
            $alert_class = "danger";
        }
    } else {
        $message = "Please select an image file.";
        $alert_class = "danger";
    }

    header("Location: admin_cart_edit.php?message=" . urlencode($message) . "&alert=" . $alert_class);
    exit();
}

// Handle Delete Decoration Item
if (isset($_GET['delete_decoration'])) {
    $delete_id = intval($_GET['delete_decoration']);

    // First, get the image file path to delete the image
    $stmt = $conn->prepare("SELECT image_url FROM decoration_items WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $image_path = $row['image_url'];
        if (file_exists($image_path)) {
            unlink($image_path); // Delete the image from the server
        }
    }
    $stmt->close();

    // Delete the item from the database
    $stmt = $conn->prepare("DELETE FROM decoration_items WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $message = "Decoration item deleted successfully!";
        $alert_class = "success";
    } else {
        $message = "Error deleting item: " . $stmt->error;
        $alert_class = "danger";
    }
    $stmt->close();

    header("Location: admin_cart_edit.php?message=" . urlencode($message) . "&alert=" . $alert_class);
    exit();
}

// Fetch existing decoration items from the database
$result = $conn->query("SELECT * FROM decoration_items ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Decoration Items</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
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
            </ul>
        </div>
    </nav>
<div class="container mt-5">
    <!-- Display Alert Messages -->
    <?php if (isset($_GET['message'])): ?>
        <div class="alert alert-<?php echo $_GET['alert']; ?>">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>

    <!-- Decoration Items Management Section -->
    <h2 class="text-center"><i class="fas fa-paint-brush mr-2"></i>Manage Decoration Items</h2>

    <!-- Decoration Item Form -->
    <form action="admin_cart_edit.php" method="POST" enctype="multipart/form-data" class="mb-4">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="item_name"><i class="fas fa-paint-brush mr-2"></i>Item Name</label>
                <input type="text" class="form-control" id="item_name" name="item_name" placeholder="Enter item name" required>
            </div>
            <div class="form-group col-md-4">
                <label for="item_price"><i class="fas fa-money-bill-wave mr-2"></i>Price (Rs.)</label>
                <input type="number" class="form-control" id="item_price" name="item_price" placeholder="Enter price" min="0" step="0.01" required>
            </div>
            <div class="form-group col-md-4">
                <label for="item_image"><i class="fas fa-image mr-2"></i>Item Image</label>
                <input type="file" class="form-control-file" id="item_image" name="item_image" accept="image/*" required>
            </div>
        </div>
        <button type="submit" name="add_decoration_item" class="btn btn-success"><i class="fas fa-plus mr-2"></i>Add Decoration Item</button>
    </form>

    <!-- Existing Decoration Items Table -->
    <h3 class="text-center mb-4">Existing Decoration Items</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Price (Rs.)</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['price']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['item_name']); ?>" width="100"></td>
                        <td>
                            <a href="admin_cart_edit.php?delete_decoration=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fas fa-trash-alt"></i> Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No decoration items found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
