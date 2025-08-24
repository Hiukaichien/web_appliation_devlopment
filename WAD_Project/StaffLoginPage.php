<!DOCTYPE html>
<html lang="en">

<head>
    <title>Staff Login Page</title>
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

        function validateForm() {
            var username = document.forms["loginForm"]["username"].value;
            var password = document.forms["loginForm"]["password"].value;
            if (username == "") {
                alert("Username cannot be empty");
                return false;
            }
            if (password == "") {
                alert("Password cannot be empty");
                return false;
            }
            return true;
        }
    </script>
</head>

<body class="bodystafflogin">
    <div class="mainstafflogin">
        <!-- Login Form -->
        <div class="stafflogin">
            <form name="loginForm" method="POST" action="" onsubmit="return validateForm()">
                <label for="chk" aria-hidden="true">Staff Login</label>
                <input type="text" name="username" placeholder="Username" required="">
                <div class="password-container">
                    <input type="password" id="login_password" name="password" placeholder="Password" required="">
                    <i class="fas fa-eye" id="login_password-icon" onclick="togglePasswordVisibility('login_password')"></i>
                </div>
                <button type="submit" name="login">Login</button>
                <a href="CustomerLoginPage.php">Customer login here</a>
                <div class="staff-login-underline"></div>
            </form>
        </div>
    </div>

    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once 'DBhelper.php';
    $dbhelper = new DBhelper();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['login'])) {
            $username = htmlspecialchars($_POST['username']);
            $password = htmlspecialchars($_POST['password']);

            if ($dbhelper->validateStaffLogin($username, $password)) {
                session_start();
                $_SESSION['username'] = $username;
                header("Location: index.php");
                exit();
            } else {
                echo "<script>alert('Username/Password is invalid. Please try again.');</script>";
            }
        }
    }
    ?>
</body>

</html>