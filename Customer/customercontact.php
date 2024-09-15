<?php
    include('customerheader.php');
    include('customernav.php');
    include('../db_connect.php');
    $message = ""; // Initialize an empty message variable

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize and capture form inputs
        $full_name = $conn->real_escape_string($_POST['full_name']);
        $email = $conn->real_escape_string($_POST['email']);
        $gender = $conn->real_escape_string($_POST['gender']);
        $mobile = $conn->real_escape_string($_POST['mobile']);
        $address = $conn->real_escape_string($_POST['address']);
        $message = $conn->real_escape_string($_POST['message']);

        // Insert form data into the database
        $sql = "INSERT INTO contact (full_name, email, gender, mobile, address, message) 
                VALUES ('$full_name', '$email', '$gender', '$mobile', '$address', '$message')";
        
        if ($conn->query($sql) === TRUE) {
            $message = "Message sent successfully!";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
?>

<?php if (!empty($message)): ?>
    <script>
        alert("<?php echo $message; ?>");
    </script>
<?php endif; ?>
<link rel="stylesheet" href="../index.css">

<div class="content">
<main>
            <section class="contact-form">
                <h2>Contact Form</h2>
                <p>If you have any questions, feel free to reach out to us. We're here to help!</p>

                <form  id="contactForm" method="POST" action="customercontact.php">
                    <input type="text" id="full_name" name="full_name" placeholder="Enter Full Name" required>
                    <input type="email" id="email" name="email" placeholder="Enter Email Address" required>
                    <select id="gender" name="gender" required>
                        <option value="">Select Your Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                    <input type="tel" id="mobile" name="mobile" placeholder="Enter Mobile No" maxlength="10" required>
                    <input type="text" id="address" name="address" placeholder="Enter Address" required>
                    <textarea id="textarea" name="message" placeholder="Enter your Message" style="width: 100%; height: 100px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; line-height: 1.5; box-sizing: border-box; resize: vertical; outline: none;" required></textarea>


                    <button type="submit">Submit</button>
                </form>
            </section>

                    <section class="contact-section">
                
                        <div class="contact-info">
                            <div class="contact-item">
                                <h3>Our Address</h3>
                                <p>123 Darby Street, NewCastle, NSW</p>
                            </div>
                
                            <div class="contact-item">
                                <h3>Phone Number</h3>
                                <p>+61 45 123-4567</p>
                            </div>
                
                            <div class="contact-item">
                                <h3>Email Address</h3>
                                <p>support@thebooknook.com</p>
                            </div>
                        </div>
                
                        <div class="social-links">
                            <h3>Follow Us</h3>
                            <a href="https://www.facebook.com/" class="social-icon" target="_blank"><img src="../Media/facebook.png" alt="Facebook"></a>
                            <a href="https://www.twitter.com/" class="social-icon" target="_blank"><img src="../Media/twitter.png" alt="Twitter"></a>
                            <a href="https://www.instagram.com/" class="social-icon" target="_blank"><img src="../Media/instagram.png" alt="Instagram"></a>
                            <a href="https://www.LinkedIn.com/" class="social-icon" target="_blank"><img src="../Media/linkedin.png" alt="LinkedIn"></a>
                        </div>
                    </section>
              
        </main>
</div>

<?php
    include('../footer.php');

   

    $conn->close();
?>
