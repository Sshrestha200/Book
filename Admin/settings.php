<?php
session_start();
include('../db_connect.php');
include('adminheader.php');
include('adminnav.php');

$message = "";
$message_color = "red";  

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $conn->real_escape_string($_POST['current_password']);
    $new_password = $conn->real_escape_string($_POST['new_password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);

    if ($new_password !== $confirm_password) {
        $message = "New passwords do not match.";
    } else {
        $user_id = $_SESSION['user_id']; 
        $result = $conn->query("SELECT password FROM users WHERE id = $user_id");

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($current_password === $row['password']) {
                $hashed_password = $new_password;  
                if ($conn->query("UPDATE users SET password = '$hashed_password' WHERE id = $user_id") === TRUE) {
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
</head>

<main>
    <div class="content">
        <section class="contact-form">
            <h2>Settings</h2>
            
            <form action="settings.php" method="POST">
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
                <?php if (!empty($message)): ?>
                <p style="color: <?php echo $message_color; ?>;"><?php echo $message; ?></p>
                <?php endif; ?>
                
                <button type="submit">Change Password</button>
            </form>
        </section>
    </div>
</main>

<?php
include('../footer.php');
$conn->close();
?>
