<?php
    include('adminheader.php');
    include('adminnav.php');
    include('../db_connect.php');

    //  adding or updating a blog post
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = $conn->real_escape_string($_POST['title']);
        $category = $conn->real_escape_string($_POST['category']);

        if (isset($_POST['blog_id']) && !empty($_POST['blog_id'])) {
            // Update an existing blog post
            $blog_id = $conn->real_escape_string($_POST['blog_id']);
            $sql = "UPDATE blogs SET title='$title', category='$category' WHERE id='$blog_id'";
            if ($conn->query($sql) === TRUE) {
                echo "Blog updated successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            // Insert a new blog post
            $sql = "INSERT INTO blogs (title, category) VALUES ('$title', '$category')";
            if ($conn->query($sql) === TRUE) {
                echo "New blog posted successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    //  delete request
    if (isset($_GET['delete'])) {
        $id = $conn->real_escape_string($_GET['delete']);
        $sql = "DELETE FROM blogs WHERE id = $id";
        if ($conn->query($sql) === TRUE) {
            echo "Blog deleted successfully";
        } else {
            echo "Error: " . $conn->error;
        }
    }

 
    $result = $conn->query("SELECT * FROM blogs");
?>

<link rel="stylesheet" href="../index.css">

<div class="content">
    <main>
        <section class="blogs-section">
            <h2><?php echo isset($_GET['edit']) ? 'Edit Blog' : 'Write a New Blog'; ?></h2>
            <form action="adminblogs.php" method="POST">
                <div class="blog-form">
                    <?php
                        if (isset($_GET['edit'])) {
                            $id = $conn->real_escape_string($_GET['edit']);
                            $edit_result = $conn->query("SELECT * FROM blogs WHERE id = $id");
                            $edit_row = $edit_result->fetch_assoc();
                    ?>
                        <input type="hidden" name="blog_id" value="<?php echo $edit_row['id']; ?>">
                        <textarea name="title" placeholder="Blog Title" required><?php echo $edit_row['title']; ?></textarea><br>
                        <textarea name="category" placeholder="Blog Description" required><?php echo $edit_row['category']; ?></textarea><br>
                        <button type="submit">Update Blog</button>
                    <?php
                        } else {
                    ?>
                        <textarea name="title" placeholder="Blog Title" required></textarea><br>
                        <textarea name="category" placeholder="Blog Description" required></textarea><br>
                        <button type="submit">Post Blog</button>
                    <?php } ?>
                </div>
            </form>
        </section>

        <section class="book-list">
            <h2>Blogs</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['title']; ?></td>
                                <td><?php echo $row['category']; ?></td>
                                <td><?php echo $row['created_at']; ?></td>

                                <td>
                                    <a href="adminblogs.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a style="color:red;" href="adminblogs.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm delete-button" onclick="return confirm('Are you sure you want to delete this blog?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align:center;">No blogs available</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>

<?php
    include('../footer.php');
    $conn->close();
?>
