<?php

include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $genre = $conn->real_escape_string($_POST['genre']);
    $writer = $conn->real_escape_string($_POST['writer']);
    $description = $conn->real_escape_string($_POST['description']);
    
    // Handle file uploads
    $cover_image = $_FILES['cover_image']['name'];
    $pdf_file = $_FILES['pdf_file']['name'];

    // Define the target directories
    $target_dir_images = "uploads/images/";
    $target_dir_pdfs = "uploads/pdfs/";

    // Create the directories if they don't exist
    if (!is_dir($target_dir_images)) {
        mkdir($target_dir_images, 0777, true);
    }
    if (!is_dir($target_dir_pdfs)) {
        mkdir($target_dir_pdfs, 0777, true);
    }

    // Define the file paths
    $target_file_image = $target_dir_images . basename($cover_image);
    $target_file_pdf = $target_dir_pdfs . basename($pdf_file);

    // Move the uploaded files to the target directories
    move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file_image);
    move_uploaded_file($_FILES['pdf_file']['tmp_name'], $target_file_pdf);

    // Insert the book data into the database
    $sql = "INSERT INTO books (title, genre, writer, description, cover_image, pdf_file) 
            VALUES ('$title', '$genre', '$writer', '$description', '$cover_image', '$pdf_file')";

    if ($conn->query($sql) === TRUE) {
        echo "New book added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle delete request
if (isset($_GET['delete'])) {
    $id = $conn->real_escape_string($_GET['delete']);
    $sql = "DELETE FROM books WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Book deleted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

$result = $conn->query("SELECT * FROM books");

$conn->close();
?>

<main>



<section class="contact-form">
                <h2>Add a New Book</h2>
                <form action="admin_upload.php" method="post" enctype="multipart/form-data" class="book-form" >
            <input type="text" id="title" name="title" placeholder="Enter Book Title" required>

            <input type="text" id="genre" name="genre" placeholder="Enter Genre" required>

            <input type="text" id="writer" name="writer" placeholder="Enter Author Name" required>

            <input type="textarea" id="description" name="description" placeholder="Enter Description">

            <label for="cover_image" class="file-label">Cover Image:</label>
            <input type="file" id="cover_image" name="cover_image" accept="image/*" required>

            <label for="pdf_file" class="file-label">PDF File:</label>
            <input type="file" id="pdf_file" name="pdf_file" accept="application/pdf" required>

        <button type="submit">Upload Book</button>
    </form>
            </section>


            <section class="book-list">

    <h2>Book List</h2>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Genre</th>
                <th>Writer</th>
                <th>Description</th>
                <th>Cover Image</th>
                <th>PDF File</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td><?php echo htmlspecialchars($row['genre']); ?></td>
                    <td><?php echo htmlspecialchars($row['writer']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><img src="uploads/images/<?php echo htmlspecialchars($row['cover_image']); ?>" alt="Cover Image" width="50"></td>
                    <td><a href="uploads/pdfs/<?php echo htmlspecialchars($row['pdf_file']); ?>" target="_blank">View PDF</a></td>
                    <td>
                        <a href="admin_upload.php?edit=<?php echo $row['id']; ?>">Edit</a>
                        <a href="collection.php?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</section>

</main>
