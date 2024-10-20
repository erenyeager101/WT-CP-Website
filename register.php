<?php
// register.php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role']; // 'admin' or 'user'

    if (!empty($username) && !empty($email) && !empty($password) && !empty($role)) {
        // Check if the role is admin
        if ($role === 'admin') {
            // Count the number of existing admins
            $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE role = 'admin'");
            $stmt->execute();
            $stmt->bind_result($admin_count);
            $stmt->fetch();
            $stmt->close();

            // If there are already 3 admins, deny registration
            if ($admin_count >= 3) {
                $error = "Maximum number of admin users reached (3).";
            }
        }

        // If the number of admins is less than 3 or if the role is user, proceed with registration
        if (!isset($error)) {
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $password, $role);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Error during registration!";
            }
            $stmt->close();
        }
    } else {
        $error = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - Shantai Banquet and Lawn</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f1f5f9;
        }

        /* Split Screen */
        .split-screen {
            display: flex;
            flex: 1;
        }

        .left {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #fff;
            padding: 50px;
        }

        .left .register-wrapper {
            background: rgba(255, 255, 255, 0.6);
            border-radius: 15px;
            padding: 40px;
            max-width: 400px;
            width: 100%;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .left h2 {
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
            text-align: center;
            color: #333;
        }

        .input-field {
            position: relative;
            margin-bottom: 25px;
        }

        .input-field input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.2);
            outline: none;
            font-size: 16px;
            transition: border 0.3s ease;
        }

        .input-field input:focus {
            border-color: #007bff;
        }

        .input-field label {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            font-size: 16px;
            pointer-events: none;
            transition: top 0.3s ease, font-size 0.3s ease;
        }

        .input-field input:focus + label,
        .input-field input:not(:placeholder-shown) + label {
            top: -10px;
            font-size: 12px;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: #007bff;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
        }

        button:hover {
            background: #0056b3;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
        }

        .register-link a {
            color: #007bff;
            text-decoration: none;
        }

        /* Right Side */
        .right {
            flex: 1;
            background-image: url('img/decoration.jpg'); /* Add your image path */
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .right .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
        }
    </style>
</head>
<body>
    <div class="split-screen">
        <div class="left">
            <div class="register-wrapper">
                <h2>Register</h2>
                <?php if (isset($error)): ?>
                    <div class="alert" style="color: red; text-align: center;">
                        <?= htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form action="register.php" method="POST">
                    <div class="input-field">
                        <input type="text" id="username" name="username" required placeholder=" ">
                        <label for="username">Username</label>
                    </div>
                    <div class="input-field">
                        <input type="email" id="email" name="email" required placeholder=" ">
                        <label for="email">Email</label>
                    </div>
                    <div class="input-field">
                        <input type="password" id="password" name="password" required placeholder=" ">
                        <label for="password">Password</label>
                    </div>

                    <button type="submit">Register</button>
                </form>
                <div class="register-link">
                    <p>Already have an account? <a href="login.php">Login here</a></p>
                </div>
            </div>
        </div>
        <div class="right">
            <div class="overlay"></div>
        </div>
    </div>
</body>
</html>
