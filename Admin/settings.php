<?php
session_start();
include('../db_connect.php');
include('adminheader.php');
include('adminnav.php');

$message = "";
$message_color = "red";  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $conn->real_escape_string($_POST['current_password']);
    $new_password = $conn->real_escape_string($_POST['new_password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);

    if ($new_password !== $confirm_password) {
        $message = "New passwords do not match.";
    } 
    elseif (strlen($new_password) < 8 ||
        !preg_match('/[A-Z]/', $new_password) ||
        !preg_match('/[a-z]/', $new_password) ||
        !preg_match('/[0-9]/', $new_password) ||
        !preg_match('/[\W_]/', $new_password)) {
        $message = "New password must be at least 8 characters long, include an uppercase letter, a lowercase letter, a digit, and a special character.";
    } else {
        $user_id = $_SESSION['user_id']; 
        $result = $conn->query("SELECT password FROM users WHERE id = $user_id");

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($current_password === $row['password']) {
                $plain_password = $new_password;  
                if ($conn->query("UPDATE users SET password = '$plain_password' WHERE id = $user_id") === TRUE) {
                    $message = "Password updated successfully!";
                    $message_color = "green"; 
                } else {
                    $message = "Error updating password: " . $conn->error;
                }
            } else {
                $message = "Current password is incorrect.";
            }
        } else {
            $message = "User not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../styles.css">
    <script>
        // Client-side validation for new password and confirm password
        function validateNewPassword() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const message = document.getElementById('message');
            const submitButton = document.querySelector('button[type="submit"]');

            // Password validation rules for the new password only
            const passwordCriteria = /^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[\W_]).{8,}$/;

            if (!passwordCriteria.test(newPassword)) {
                message.style.color = 'red';
                message.textContent = "New password must be at least 8 characters long, include an uppercase letter, a lowercase letter, a digit, and a special character.";
                submitButton.disabled = true;
            } else if (newPassword !== confirmPassword) {
                message.style.color = 'red';
                message.textContent = "New passwords do not match.";
                submitButton.disabled = true;
            } else {
                message.textContent = "";
                submitButton.disabled = false;
            }
        }
    </script>
</head>

<main>
    <div class="content">
        <section class="contact-form">
            <h2>Settings</h2>
            
            <form action="settings.php" method="POST" oninput="validateNewPassword()">
                <div class="form-group">
                    <label for="current_password">Current Password:</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <p id="message" style="color: red;"></p>
                <?php if (!empty($message)): ?>
                <p style="color: <?php echo $message_color; ?>;"><?php echo $message; ?></p>
                <?php endif; ?>
                
                <button type="submit" disabled>Change Password</button>
            </form>
        </section>
    </div>
</main>

<?php
include('../footer.php');
$conn->close();
?>
