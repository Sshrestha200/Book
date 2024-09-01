<?php

include('../db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // adding or updating a book
    $title = $conn->real_escape_string($_POST['title']);
    $genre = $conn->real_escape_string($_POST['genre']);
    $writer = $conn->real_escape_string($_POST['writer']);
    $description = $conn->real_escape_string($_POST['description']);
    
    //file uploads
    $cover_image = $_FILES['cover_image']['name'];
    $pdf_file = $_FILES['pdf_file']['name'];

    // target directories
    $target_dir_images = "../uploads/images/";
    $target_dir_pdfs = "../uploads/pdfs/";

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

    // Move the uploaded files to the target directories if they exist
    if (!empty($cover_image)) {
        move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file_image);
    }

    if (!empty($pdf_file)) {
        move_uploaded_file($_FILES['pdf_file']['tmp_name'], $target_file_pdf);
    }

    if (isset($_POST['book_id']) && !empty($_POST['book_id'])) {
        // Handle updating a book
        $book_id = $conn->real_escape_string($_POST['book_id']);

        $sql = "UPDATE books SET title='$title', genre='$genre', writer='$writer', description='$description'";

        if (!empty($cover_image)) {
            $sql .= ", cover_image='$cover_image'";
        }
        if (!empty($pdf_file)) {
            $sql .= ", pdf_file='$pdf_file'";
        }

        $sql .= " WHERE id='$book_id'";

        if ($conn->query($sql) === TRUE) {
            header("Location: admincollection.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // Insert the book data into the database
        $sql = "INSERT INTO books (title, genre, writer, description, cover_image, pdf_file) 
                VALUES ('$title', '$genre', '$writer', '$description', '$cover_image', '$pdf_file')";

        if ($conn->query($sql) === TRUE) {
            header("Location: admincollection.php"); 
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// delete request
if (isset($_GET['delete'])) {
    $id = $conn->real_escape_string($_GET['delete']);
    
    // Get the file paths before deleting the record
    $file_query = $conn->query("SELECT cover_image, pdf_file FROM books WHERE id = $id");
    if ($file_query->num_rows > 0) {
        $file_row = $file_query->fetch_assoc();
        
        // Delete files from the server
        if (file_exists("../uploads/images/" . $file_row['cover_image'])) {
            unlink("../uploads/images/" . $file_row['cover_image']);
        }
        if (file_exists("../uploads/pdfs/" . $file_row['pdf_file'])) {
            unlink("../uploads/pdfs/" . $file_row['pdf_file']);
        }
    }
    
    $sql = "DELETE FROM books WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: admincollection.php"); 
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}


$result = $conn->query("SELECT * FROM books");

?>

<?php
    include('adminheader.php');
    include('adminnav.php');    
?>
    <link rel="stylesheet" href="../index.css">

<main>

<section class="contact-form">
    <h2><?php echo isset($_GET['edit']) ? 'Edit' : 'Add a New'; ?> Book</h2>
    <form action="<?php echo basename($_SERVER['REQUEST_URI']); ?>" method="post" enctype="multipart/form-data" class="book-form">
        <?php
            if (isset($_GET['edit'])) {
                $id = $conn->real_escape_string($_GET['edit']);
                $edit_result = $conn->query("SELECT * FROM books WHERE id = $id");
                $edit_row = $edit_result->fetch_assoc();
        ?>
                <input type="hidden" name="book_id" value="<?php echo $edit_row['id']; ?>">
                <label>Title</label>
                <input type="text" id="title" name="title" value="<?php echo $edit_row['title']; ?>" placeholder="Enter Book Title" required>
                <label>Genre</label>
                <input type="text" id="genre" name="genre" value="<?php echo $edit_row['genre']; ?>" placeholder="Enter Genre" required>
                <label>Author</label>
                <input type="text" id="writer" name="writer" value="<?php echo $edit_row['writer']; ?>" placeholder="Enter Author Name" required>
                <label>Description</label>
                <input type="textarea" id="description" name="description" class="form-control" value="<?php echo $edit_row['description']; ?>" placeholder="Enter Description" required>
                <label>Cover Image</label>
                <?php if (!empty($edit_row['cover_image'])): ?>
                    <br>
                    <img src="../uploads/images/<?php echo $edit_row['cover_image']; ?>" alt="Cover Image" width="100"><br>
                <?php endif; ?>
                <input type="file" id="cover_image" name="cover_image" accept="image/*" class="form-control">
                <label>PDF File</label>
                <?php if (!empty($edit_row['pdf_file'])): ?>
                    <br>
                    <a href="../uploads/pdfs/<?php echo $edit_row['pdf_file']; ?>" target="_blank">View Current PDF</a><br>
                <?php endif; ?>
                <input type="file" id="pdf_file" name="pdf_file" accept="application/pdf" class="form-control">
                <button type="submit" class="btn btn-primary">Update Book</button>
        <?php
            } else {
        ?>
                <label>Title</label>
                <input type="text" id="title" name="title" placeholder="Enter Book Title" required>
                <label>Genre</label>
                <input type="text" id="genre" name="genre" placeholder="Enter Genre" required>
                <label>Writer</label>
                <input type="text" id="writer" name="writer" placeholder="Enter Author Name" required>
                <label>Description</label>
                <input type="textarea" id="description" name="description" class="form-control" placeholder="Enter Description" required></input>
                <label>Cover Image</label>
                <input type="file" id="cover_image" name="cover_image" accept="image/*" required class="form-control">
                <label>PDF File</label>
                <input type="file" id="pdf_file" name="pdf_file" accept="application/pdf" required class="form-control">
                <button type="submit" class="btn btn-primary">Upload Book</button>
        <?php } ?>
    </form>
</section>

<section class="book-list">
    <h2>Book List</h2>
    <table class="table table-striped">
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
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['genre']; ?></td>
                    <td><?php echo $row['writer']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><img src="../uploads/images/<?php echo $row['cover_image']; ?>" alt="Cover Image" width="50"></td>
                    <td><a href="../uploads/pdfs/<?php echo $row['pdf_file']; ?>" target="_blank">View PDF</a></td>
                    <td>
                        <a href="admincollection.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a style="color:red;" href="admincollection.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm delete-button" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table
    </section>

</main>
<?php
$conn->close(); // Close the connection here, after all operations
include('../footer.php');
?>