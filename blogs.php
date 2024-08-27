<?php
    include('header.php');
    include('nav.php');
    include('db_connect.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize input data to prevent SQL injection
        $title = $conn->real_escape_string($_POST['title']);
        $category = $conn->real_escape_string($_POST['category']);

        // Insert the blog into the database
        $sql = "INSERT INTO blogs (title, category) VALUES ('$title', '$category')";
        
        if ($conn->query($sql) === TRUE) {
            echo "New blog posted successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $result = $conn->query("SELECT * FROM blogs");
    echo '<script>console.log(' . json_encode($result) . ');</script>';
    $conn->close();

?>

<div class="content">

        <main>
            <section class="blogs-section">
                <h2>Blogs</h2>
                <?php while($row = $result->fetch_assoc()): ?>
                <div class="blog-card">
                    <h3><?php echo $row['title']; ?></h3>
                    <p><?php echo $row['category']; ?></p>
                    </div>
                    <?php endwhile; ?>    
                    
                <form  action="blogs.php" method="POST">
                <div class="blog-form">
                <h4>Write your Blogs here...</h4>  
                    <textarea type="text" name="title" placeholder="Blog Title" required></textarea><br>
                    <textarea name="category" placeholder="Blog Description" required></textarea><br>
                    <button type="submit">Post</button>
                </div>
                </form>
            </section>
        </main>
</div>

<?php
    include('footer.php');
?>
