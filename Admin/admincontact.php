<?php
    include('adminheader.php');
    include('adminnav.php');
    include('../db_connect.php');
    $message = ""; 

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $full_name = $conn->real_escape_string($_POST['full_name']);
        $email = $conn->real_escape_string($_POST['email']);
        $gender = $conn->real_escape_string($_POST['gender']);
        $mobile = $conn->real_escape_string($_POST['mobile']);
        $address = $conn->real_escape_string($_POST['address']);
        $textarea = $conn->real_escape_string($_POST['textarea']);

        if (isset($_POST['contact_id']) && !empty($_POST['contact_id'])) {
            // Update an existing contact
            $contact_id = $conn->real_escape_string($_POST['contact_id']);
            $sql = "UPDATE contact SET full_name='$full_name', email='$email', gender='$gender', mobile='$mobile', address='$address', message='$textarea' WHERE id='$contact_id'";
            if ($conn->query($sql) === TRUE) {
                header("Location: admincontact.php?message=updated"); 
                exit();
            } else {
                $message = "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            // Insert a new contact
            $sql = "INSERT INTO contact (full_name, email, gender, mobile, address, message) 
                    VALUES ('$full_name', '$email', '$gender', '$mobile', '$address', '$textarea')";
            if ($conn->query($sql) === TRUE) {
                header("Location: admincontact.php?message=inserted"); 
                exit();
            } else {
                $message = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    // Handle delete request
    if (isset($_GET['delete'])) {
        $id = $conn->real_escape_string($_GET['delete']);
        $sql = "DELETE FROM contact WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            header("Location: admincontact.php?message=deleted"); 
            exit();
        } else {
            $message = "Error: " . $conn->error;
        }
    }

    // Fetch all contact information
    $result = $conn->query("SELECT * FROM contact");
?>

<?php if (isset($_GET['message'])): ?>
    <script>
        alert("<?php echo ucfirst($_GET['message']); ?> successfully!");
    </script>
<?php endif; ?>

<link rel="stylesheet" href="../index.css">

<div class="content">
    <main>
        <section class="contact-form">
            <h2>Contact Form</h2>
            <p>If you have any questions, feel free to reach out to us. We're here to help!</p>

            <form id="contactForm" method="POST" action="admincontact.php">
                <?php
                    if (isset($_GET['edit'])) {
                        $id = $conn->real_escape_string($_GET['edit']);
                        $edit_result = $conn->query("SELECT * FROM contact WHERE id = $id");
                        $edit_row = $edit_result->fetch_assoc();
                ?>
                    <input type="hidden" name="contact_id" value="<?php echo $edit_row['id']; ?>">
                    <input type="text" id="full_name" name="full_name" placeholder="Enter Full Name" value="<?php echo $edit_row['full_name']; ?>" required>
                    <input type="email" id="email" name="email" placeholder="Enter Email Address" value="<?php echo $edit_row['email']; ?>" required>
                    <select id="gender" name="gender" required>
                        <option value="">Select Your Gender</option>
                        <option value="male" <?php if($edit_row['gender'] == 'male') echo 'selected'; ?>>Male</option>
                        <option value="female" <?php if($edit_row['gender'] == 'female') echo 'selected'; ?>>Female</option>
                        <option value="other" <?php if($edit_row['gender'] == 'other') echo 'selected'; ?>>Other</option>
                    </select>
                    <input type="tel" id="mobile" name="mobile" placeholder="Enter Mobile No" maxlength="10" value="<?php echo $edit_row['mobile']; ?>" required>
                    <input type="text" id="address" name="address" placeholder="Enter Address" value="<?php echo $edit_row['address']; ?>" required>
                    <textarea id="textarea" name="textarea" placeholder="Enter your Message" style="width: 100%; height: 100px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; line-height: 1.5; box-sizing: border-box; resize: vertical; outline: none;">
<?php echo $edit_row['message']; ?>
</textarea>

                    <button type="submit">Update Contact</button>
                <?php
                    } else {
                ?>
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
                    <textarea id="textarea" name="message" placeholder="Enter your Message" style="width: 100%; height: 100px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; line-height: 1.5; box-sizing: border-box; resize: vertical; outline: none;"></textarea>
                    <button type="submit">Submit</button>
                <?php } ?>
            </form>
        </section>

        <section class="book-list">
            <h2>Contact Information</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Mobile</th>
                        <th>Address</th>
                        <th>Message</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['full_name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['gender']; ?></td>
                                <td><?php echo $row['mobile']; ?></td>
                                <td><?php echo $row['address']; ?></td>
                                <td><?php echo $row['message']; ?></td>
                                <td>
                                    <a href="admincontact.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a  style="color:red;" href="admincontact.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this contact?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align:center;">No contact information available</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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
