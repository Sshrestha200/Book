<?php
    include('customerheader.php');
    include('customernav.php');
    include('../db_connect.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = $conn->real_escape_string($_POST['title']);
        $category = $conn->real_escape_string($_POST['category']);

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
    <link rel="stylesheet" href="../index.css">

<div class="content">

        <main>
            <section class="blogs-section">
                <h2>Blogs</h2>
                <?php while($row = $result->fetch_assoc()): ?>
                <div class="blog-card">
                    <h3><?php echo $row['title']; ?></h3>
                    <h4><?php echo $row['category']; ?></h4>
                    <p><?php echo $row['created_at']; ?></p>

                    </div>
                    <?php endwhile; ?>    
                    
                <form  action="customerblogs.php" method="POST">
                <div class="blog-form">
                <h4>Write your Blogs here...</h4>  
                    <input type="text" name="title" placeholder="Blog Title" required></input><br>
                    <textarea name="category" placeholder="Blog Description" required></textarea><br>
                    <button type="submit">Post</button>
                </div>
                </form>
            </section>
        </main>
</div>

<?php
    include('../footer.php');
?>
