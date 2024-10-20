<?php
// admin_list.php

session_start();
include 'config.php';

// Ensure only admins can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Check if the 'username' key exists in the session
$current_admin_username = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Handle Admin Deletion
if (isset($_GET['delete'])) {
    $delete_username = $_GET['delete'];

    // Check if the user is trying to delete themselves
    if ($delete_username == $current_admin_username) {
        $message = "You cannot delete yourself!";
        $alert_class = "danger";
    } else {
        // Prevent the last admin from being deleted
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE role = 'admin'");
        $stmt->execute();
        $stmt->bind_result($admin_count);
        $stmt->fetch();
        $stmt->close();

        if ($admin_count <= 1) {
            $message = "You cannot delete the last remaining admin!";
            $alert_class = "danger";
        } else {
            // Delete the admin
            $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
            $stmt->bind_param("s", $delete_username);
            if ($stmt->execute()) {
                $message = "Admin deleted successfully!";
                $alert_class = "success";
            } else {
                $message = "Error: Unable to delete admin!";
                $alert_class = "danger";
            }
            $stmt->close();
        }
    }

    header("Location: admin_list.php?message=" . urlencode($message) . "&alert=" . $alert_class);
    exit();
}

// Handle Admin Registration
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash the password

    // Check if the user already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "Email already exists!";
        $alert_class = "danger";
    } else {
        // Insert the new admin into the database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'admin')");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            $message = "New admin registered successfully!";
            $alert_class = "success";
        } else {
            $message = "Error: Could not register admin!";
            $alert_class = "danger";
        }
        $stmt->close();
    }
}

$admins_result = $conn->query("SELECT * FROM users WHERE role = 'admin' ORDER BY username ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin List - Exploreocity</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#"><i class="fas fa-calendar-alt mr-2"></i>Exploreocity Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdownAdmin" aria-controls="navbarNavDropdownAdmin" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdownAdmin">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="admin_dashboard.php"><i class="fas fa-home mr-1"></i>Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="admin_list.php"><i class="fas fa-users-cog mr-1"></i>Admin List</a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End of Navbar -->

    <div class="container mt-5">
        <h2 class="text-center">Admin List</h2>

        <?php if (isset($_GET['message']) || isset($message)): ?>
            <div class="alert alert-<?= isset($alert_class) ? $alert_class : $_GET['alert']; ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars(isset($message) ? $message : $_GET['message']); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($admin = $admins_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($admin['username']); ?></td>
                        <td><?= htmlspecialchars($admin['email']); ?></td>
                        <td>
                            <?php if ($admin['username'] != $current_admin_username): ?>
                                <a href="admin_list.php?delete=<?= urlencode($admin['username']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this admin?');">Delete</a>
                            <?php else: ?>
                                <span class="text-muted">Cannot delete yourself</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Admin Registration Form -->
        <div class="mt-5">
            <h3 class="text-center">Register New Admin</h3>
            <form method="post" action="admin_list.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" name="register" class="btn btn-primary">Register Admin</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
