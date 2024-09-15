<?php
include('../db_connect.php');
$query = "SELECT title, author, description, image_url FROM featurebooks";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<body>

    <main>
        <section class="home-section">
            <div class="home-content">
                <h1>Welcome to The Book Nook</h1>
                <p>Your cozy corner for discovering the world of literature.</p>
            </div>
        </section>
    
        <section class="about-us">
            <h2>About The Book Nook</h2>
            <p>At The Book Nook, we believe that every reader deserves a special place where stories come to life. Established in 2020, our mission is to connect people with books that inspire, educate, and entertain. Whether you're diving into the latest bestsellers, exploring timeless classics, or seeking new genres, The Book Nook is here to guide your journey. We carefully curate our collection to offer a diverse range of books, ensuring there's something for every reader. Join us in celebrating the joy of reading, one page at a time.</p>
        </section>
    
        <section class="featured-books">
            <h2>Featured Books</h2>
            <div class="book-grid">
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="book-card">';
                    echo '<img src="../uploads/images/' . $row['image_url'] . '" alt="' . $row['title'] . '">';
                    echo '<h3>' . $row['title'] . '</h3>';
                    echo '<p>By ' . $row['author'] . '</p>';
                    echo '<p>' . $row['description'] . '</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </section>

        <section class="why-choose-us">
            <h2>Why The Book Nook?</h2>
            <ul>
                <li><strong>Curated Selections:</strong> Our team of literary experts handpicks each book, ensuring that our collection is diverse, inclusive, and of the highest quality.</li>
                <li><strong>Easy-to-Use Interface:</strong> Navigate our website with ease, find the books you love, and discover new favorites with just a few clicks.</li>
                <li><strong>Diverse Genres:</strong> From fiction and non-fiction to children's books and niche genres, our collection spans across all literary tastes.</li>
                <li><strong>Personalized Recommendations:</strong> Based on your reading history and preferences, we suggest books that match your unique interests.</li>
            </ul>
        </section>
    
        <section class="testimonials">
            <h2>What Our Readers Say</h2>
            <p>"The Book Nook is my go-to for discovering new reads. The curated selections always surprise me, and the personalized recommendations have introduced me to books I wouldn't have found on my own." – Emily R.</p>
            <p>"I love the user-friendly design of The Book Nook. It's so easy to find what I'm looking for, and the collection is just incredible!" – Michael T.</p>
        </section>
    </main>
    
    <script src="script.js" defer></script>
</body>
</html>
