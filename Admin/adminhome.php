<?php
// Include the database connection
include('../db_connect.php');

// Check the number of featured books in the table
$current_books_result = $conn->query("SELECT COUNT(*) as total FROM featurebooks");
$current_books_count = $current_books_result->fetch_assoc()['total'];

// Adding or updating a book
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($current_books_count < 3 || isset($_POST['book_id'])) { // Allow adding or updating if < 3 books
        $title = $conn->real_escape_string($_POST['title']);
        $author = $conn->real_escape_string($_POST['author']);
        $description = $conn->real_escape_string($_POST['description']);
        $cover_image = $_FILES['cover_image']['name'];

        // Target directory for file uploads
        $target_dir_images = "../uploads/images/";
        $target_file_image = $target_dir_images . basename($cover_image);

        // Create directories if they don't exist
        if (!is_dir($target_dir_images)) {
            mkdir($target_dir_images, 0777, true);
        }

        // Move uploaded files
        if (!empty($cover_image)) {
            move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file_image);
        }

        if (isset($_POST['book_id']) && !empty($_POST['book_id'])) {
            // Update existing book
            $book_id = $conn->real_escape_string($_POST['book_id']);
            $sql = "UPDATE featurebooks SET title='$title', author='$author', description='$description'";

            if (!empty($cover_image)) {
                $sql .= ", image_url='$cover_image'";
            }

            $sql .= " WHERE id='$book_id'";

            if ($conn->query($sql) === TRUE) {
                header("Location: admindashboard.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            // Insert new book (only if < 3 books)
            if ($current_books_count < 3) {
                $sql = "INSERT INTO featurebooks (title, author, description, image_url) 
                        VALUES ('$title', '$author', '$description', '$cover_image')";

                if ($conn->query($sql) === TRUE) {
                    header("Location: admindashboard.php");
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "You cannot add more than 3 featured books.";
            }
        }
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = $conn->real_escape_string($_GET['delete']);

    // Delete the book record and its image
    $file_query = $conn->query("SELECT image_url FROM featurebooks WHERE id = $id");
    if ($file_query->num_rows > 0) {
        $file_row = $file_query->fetch_assoc();
        if (file_exists("../uploads/images/" . $file_row['image_url'])) {
            unlink("../uploads/images/" . $file_row['image_url']);
        }
    }

    $sql = "DELETE FROM featurebooks WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: admindashboard.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch all featured books
$result = $conn->query("SELECT * FROM featurebooks");
?>


<main>
<section class="contact-form">
    <h2><?php echo isset($_GET['edit']) ? 'Edit' : 'Add a New'; ?> Featured Book</h2>
    <?php if ($current_books_count >= 3 && !isset($_GET['edit'])): ?>
        <p>You have reached the limit of 3 featured books. Please delete an existing book to add a new one.</p>
    <?php else: ?>
    <form action="<?php echo basename($_SERVER['REQUEST_URI']); ?>" method="post" enctype="multipart/form-data">
        <?php if (isset($_GET['edit'])): 
            $id = $conn->real_escape_string($_GET['edit']);
            $edit_result = $conn->query("SELECT * FROM featurebooks WHERE id = $id");
            $edit_row = $edit_result->fetch_assoc();
        ?>
            <input type="hidden" name="book_id" value="<?php echo $edit_row['id']; ?>">
            <label>Title</label>
            <input type="text" name="title" value="<?php echo $edit_row['title']; ?>" required>
            <label>Author</label>
            <input type="text" name="author" value="<?php echo $edit_row['author']; ?>" required>
            <label>Description</label>
            <textarea id="description" name="description" placeholder="description" style="width: 100%; height: 100px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; line-height: 1.5; box-sizing: border-box; resize: vertical; outline: none;" required><?php echo $edit_row['description']; ?></textarea>
            <label>Cover Image</label><br>
            <?php if (!empty($edit_row['image_url'])): ?>
                <img src="../uploads/images/<?php echo $edit_row['image_url']; ?>" width="100"><br>
            <?php endif; ?>
            <input type="file" name="cover_image" accept="image/*" >
            <button type="submit">Update Book</button>
        <?php else: ?>
            <label>Title</label>
            <input type="text" name="title" required>
            <label>Author</label>
            <input type="text" name="author" required>
            <label>Description</label>
            <textarea id="description" name="description" placeholder="description" style="width: 100%; height: 100px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; font-size: 14px; line-height: 1.5; box-sizing: border-box; resize: vertical; outline: none;"></textarea>
            <label>Cover Image</label><br>
            <input type="file" name="cover_image" accept="image/*" required>
            <button type="submit">Add Book</button>
        <?php endif; ?>
    </form>
    <?php endif; ?>
</section>

<section class="book-list">
    <h2>Featured Book List</h2>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Description</th>
                <th>Cover Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['author']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><img src="../uploads/images/<?php echo $row['image_url']; ?>" width="50"></td>
                <td>
                    <a href="admindashboard.php?edit=<?php echo $row['id']; ?>">Edit</a>
                    <a href="admindashboard.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>
</main>

<?php
$conn->close();
?>
