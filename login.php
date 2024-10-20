<?php
// login.php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_or_email = trim($_POST['username_or_email']);
    $password = $_POST['password'];

    if (empty($username_or_email) || empty($password)) {
        $error = "All fields are required!";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username_or_email, $username_or_email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $username, $hashed_password, $role);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;

                if ($role == 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $error = "Invalid password!";
            }
        } else {
            $error = "No user found with that username/email!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - Shantai Banquet and Lawn</title>
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

        .left .login-wrapper {
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

        .register {
            text-align: center;
            margin-top: 20px;
        }

        .register a {
            color: #007bff;
            text-decoration: none;
        }

        /* Social Media Buttons */
        .social-login {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .social-btn {
            width: 48%;
            padding: 10px;
            font-size: 14px;
            text-align: center;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            color: #fff;
        }

        .google-btn {
            background-color: #db4437;
        }

        .facebook-btn {
            background-color: #3b5998;
        }

        .forgot-password {
            text-align: center;
            margin-top: 10px;
        }

        .forgot-password a {
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
            <div class="login-wrapper">
                <h2>Login</h2>

                <?php if (isset($error)): ?>
                    <div class="alert">
                        <?= htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <div class="input-field">
                        <input type="text" id="username_or_email" name="username_or_email" required placeholder=" ">
                        <label for="username_or_email">Username or Email</label>
                    </div>
                    <div class="input-field">
                        <input type="password" id="password" name="password" required placeholder=" ">
                        <label for="password">Password</label>
                    </div>
                    <button type="submit">Login</button>
                </form>

                <div class="forgot-password">
                    <a href="forgot_password.php">Forgot Password?</a>
                </div>
                <div class="register">
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </div>
        </div>
        <div class="right">
            <div class="overlay"></div>
        </div>
    </div>
</body>
</html>
