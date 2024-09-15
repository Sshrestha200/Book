<?php
include('db_connect.php');
session_start();

$show_admin_form = false;
$show_customer_info = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role']; 

    if ($role === 'admin') {
        $show_admin_form = true;  
        $username = $_POST['username'];
        $password = $_POST['password'];

        $login_query = $conn->query("SELECT * FROM users WHERE username = '$username'");

        if ($login_query->num_rows == 1) {
            $user_data = $login_query->fetch_assoc();
            
            if ($password === $user_data['password']) {
                $_SESSION['user_id'] = $user_data['id'];
                $_SESSION['role'] = 'admin';  
                setcookie('user_id', $user_data['id'], time() + (86400 * 30), "/"); 
                
                header("Location: admin/admindashboard.php");
                exit();
            } else {
                $login_error = "Invalid password.";
            }
        } else {
            $login_error = "Invalid username.";
        }
    } else if ($role === 'customer') {
        $show_customer_info = true;  
    }
}
?>

<?php
include('header.php');
include('banner.php');
?>
<!-- FontAwesome link for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<link rel="stylesheet" href="index.css">
<style>
    /* Style for the password container */
    .password-container {
        position: relative;
        width: 100%;
        max-width: 400px;
    }

    /* Style for the password input */
    .password-container input[type="password"], 
    .password-container input[type="text"] {
        width: 100%;
        padding-right: 40px; /* Make room for the toggle icon */
        padding: 10px;
        font-size: 13px;
        box-sizing: border-box;
    }

    /* Style for the toggle button */
    .password-container .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        font-size: 18px;
        color: #555;
    }

    .password-container .toggle-password:focus {
        outline: none;
    }
</style>
<div class="content">
<body>
    <div class="login-container">
        <h1>Welcome, Please Choose Your Role</h1>

        <form action="" method="POST">
            <div class="role-selection">
                <label>
                    <input type="radio" name="role" id="admin-btn" value="admin" required <?php if ($show_admin_form) echo 'checked'; ?>> Admin
                </label>
                <label>
                    <input type="radio" name="role" id="customer-btn" value="customer" required <?php if ($show_customer_info) echo 'checked'; ?>> Customer
                </label>
            </div>

            <div id="login-form" style="display: <?php echo $show_admin_form ? 'block' : 'none'; ?>;">
            <hr><br>
                <h3>Admin Login</h3>
                <input type="text" name="username" placeholder="Username" required>
                
                <div class="password-container">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <button type="button" class="toggle-password">
                        <i class="far fa-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
                
                <button type="submit">Login</button>
            </div>

            <div id="customer-info" style="display: <?php echo $show_customer_info ? 'block' : 'none'; ?>;">
                <button type="button" onclick="window.location.href='Customer/customerdashboard.php';">Go to Customer Dashboard</button>
            </div>
        </form>

        <!-- Display any login errors -->
        <?php if (isset($login_error)): ?>
            <p style="color:red;"><?php echo $login_error; ?></p>
        <?php endif; ?>
    </div>

    <script>
        // Toggle password visibility
        const togglePasswordButton = document.querySelector('.toggle-password');
        const passwordInput = document.getElementById('password');
        const togglePasswordIcon = document.getElementById('togglePasswordIcon');

        togglePasswordButton.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle between eye and eye-slash icons
            togglePasswordIcon.classList.toggle('fa-eye-slash');
            togglePasswordIcon.classList.toggle('fa-eye');
        });

        // Toggle between Admin and Customer login views
        document.getElementById('admin-btn').addEventListener('click', function() {
            document.getElementById('login-form').style.display = 'block';
            document.getElementById('customer-info').style.display = 'none';
        });

        document.getElementById('customer-btn').addEventListener('click', function() {
            document.getElementById('login-form').style.display = 'none';
            document.getElementById('customer-info').style.display = 'block';
        });
    </script>
</body>
</div>

<?php
include('footer.php');
?>

</html>
