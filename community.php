<?php
    include('header.php');
    include('nav.php');
 include('db_connect.php'); // Ensure this is included to connect to the database

    // Fetch community content from the database
    $result = $conn->query("SELECT * FROM community");
?>


<main>
        <h2>Community & Partnerships</h2>
    <section class="community-partnerships">
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?> 
            <div class="post-card">
            <h3><?php echo htmlspecialchars($row['event']); ?></h3>
                <h4><?php echo htmlspecialchars($row['title']); ?></h4>
                <?php if (!empty($row['date']) || !empty($row['time'])): ?>
            <p><strong>Date & Time:</strong>
                <?php echo !empty($row['date']) ? htmlspecialchars($row['date']) : ''; ?>
                <?php echo !empty($row['time']) ? htmlspecialchars($row['time']) : ''; ?>
            </p>
        <?php endif; ?>                <p><strong>Description:</strong>  <?php echo htmlspecialchars($row['description']); ?></p>
            </div>
        
        <?php endwhile; ?>
    <?php else: ?>
        <p>No community events available at the moment. Please check back later.</p>
    <?php endif; ?>
    </section>
    <!-- <section class="community-partnerships">
    
            <div class="post-card">
                <h3>Community Events</h3>
                <p><strong>Event Title:</strong> Annual Book Fair</p>
                <p><strong>Date & Time:</strong> September 25, 2024, 10:00 AM - 4:00 PM</p>
                <p><strong>Description:</strong> Join us for our Annual Book Fair where you can find a wide variety of books at discounted prices. There will be guest authors, book signings, and more.</p>
            </div>
    
        </section> -->
    </main>

<?php
    include('footer.php');
    $conn->close();
?>
