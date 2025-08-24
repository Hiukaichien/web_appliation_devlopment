<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script>
        function togglePasswordVisibility(id) {
            var passwordField = document.getElementById(id);
            var icon = document.getElementById(id + '-icon');
            if (passwordField.type === "password") {
                passwordField.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function validateEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function validateForm() {
            var email = document.forms["signupForm"]["email"].value;
            if (!validateEmail(email)) {
                alert("Please enter a valid email address.");
                return false;
            }
            return true;
        }
    </script>
</head>

<body class="bodylogin">
    <div class="mainlogin">
        <input type="checkbox" id="chk" aria-hidden="true">

        <!-- Signup Form -->
        <div class="signup">
            <form name="signupForm" method="POST" action="" onsubmit="return validateForm()">
                <label for="chk" aria-hidden="true">Sign up</label>
                <input type="text" name="username" placeholder="User name" required="">
                <input type="email" name="email" placeholder="Email" required="">
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Password" required="">
                    <i class="fas fa-eye" id="password-icon" onclick="togglePasswordVisibility('password')"></i>
                </div>
                <div class="password-container">
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required="">
                    <i class="fas fa-eye" id="confirm_password-icon" onclick="togglePasswordVisibility('confirm_password')"></i>
                </div>
                <button type="submit" name="signup">Sign up</button>
            </form>
        </div>

        <!-- Login Form -->
        <div class="login">
            <form method="POST" action="">
                <label for="chk" aria-hidden="true">Login</label>
                <input type="text" name="username" placeholder="Username" required="">
                <div class="password-container">
                    <input type="password" id="login_password" name="password" placeholder="Password" required="">
                    <i class="fas fa-eye" id="login_password-icon" onclick="togglePasswordVisibility('login_password')"></i>
                </div>
                <button type="submit" name="login">Login</button>
                <a href="StaffLoginPage.php">Staff login here</a>
                <div class="staff-login-underline"></div>
            </form>
        </div>
    </div>

    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $host = 'localhost';
    $db_name = 'groupassignment';
    $db_user = 'root';
    $db_password = '';

    $conn = new mysqli($host, $db_user, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['signup'])) {
            $username = htmlspecialchars($_POST['username']);
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);
            $confirm_password = htmlspecialchars($_POST['confirm_password']);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo "<script>alert('Invalid email format. Please try again.');</script>";
            } elseif ($password !== $confirm_password) {
                echo "<script>alert('Passwords do not match. Please try again.');</script>";
            } else {
                // Check if email already exists
                $stmt = $conn->prepare("SELECT customeremail FROM customer WHERE customeremail = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    echo "<script>alert('This email is already registered. Please use a different email.');</script>";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                    $stmt = $conn->prepare("INSERT INTO customer (customerusername, customeremail, customerpassword) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $username, $email, $hashed_password);
                    if ($stmt->execute()) {
                        echo "<script>alert('Sign-up successful! Welcome, $username');</script>";
                    } else {
                        echo "<script>alert('Error: Could not complete sign-up. Please try again.');</script>";
                    }
                }
                $stmt->close();
            }
        }

        if (isset($_POST['login'])) {
            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);
            $stmt = $conn->prepare("SELECT customerpassword FROM customer WHERE customerusername = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($hashed_password);
                $stmt->fetch();
                if (password_verify($password, $hashed_password)) {
                    echo "<script>alert('Customer login is under maintenance. Please try again later.');</script>";
                    exit();
                } else {
                    echo "<script>alert('Invalid password. Please try again.');</script>";
                }
            } else {
                echo "<script>alert('No user found with this username. Please sign up first.');</script>";
            }

            $stmt->close();
        }
    }
    $conn->close();
    ?>
</body>

</html>