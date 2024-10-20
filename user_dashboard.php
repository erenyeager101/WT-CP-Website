<?php
session_start();
require 'config.php';

if ($_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}

// Fetch all available dates
$dates = $conn->query("SELECT date FROM availability");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review = trim($_POST['review']);

    if (!empty($review)) {
        $stmt = $conn->prepare("INSERT INTO reviews (user_id, review) VALUES (?, ?)");
        $stmt->bind_param("is", $_SESSION['user_id'], $review);
        $stmt->execute();
        $stmt->close();
        $success = "Review submitted!";
    } else {
        $error = "Please enter a review!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f0f0;
            padding: 20px;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            padding: 10px;
            background-color: #f9f9f9;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome <?= htmlspecialchars($_SESSION['username']); ?></h2>

        <h3>Available Dates</h3>
        <ul>
            <?php while ($row = $dates->fetch_assoc()): ?>
                <li><?= htmlspecialchars($row['date']); ?></li>
            <?php endwhile; ?>
        </ul>

        <h3>Submit Your Review</h3>

        <?php if (isset($success)): ?>
            <div><?= htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="user_dashboard.php" method="POST">
            <textarea name="review" required placeholder="Enter your review here"></textarea>
            <button type="submit">Submit Review</button>
        </form>

        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
