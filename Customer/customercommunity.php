<?php
    include('customerheader.php');
    include('customernav.php');
    include('../db_connect.php'); 

    $result = $conn->query("SELECT * FROM community");
?>
<link rel="stylesheet" href="../index.css">

<main>
    <h2>Community & Partnerships</h2>
    <section class="community-partnerships">
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?> 
            <div class="post-card">
                <h3><?php echo $row['event']; ?></h3>
                <h4><?php echo $row['title']; ?></h4>
                
                <?php if (!empty($row['start_date']) || !empty($row['start_time'])): ?>
                    <p><strong>Date:</strong>
                        <?php echo !empty($row['start_date']) ? $row['start_date'] : ''; ?>
                        <?php echo !empty($row['start_time']) ? $row['start_time'] : ''; ?>

                        </p>
                        <p><strong>Time:</strong>

                        <?php if (!empty($row['end_date']) || !empty($row['end_time'])): ?>
                            <?php echo ' to '; ?>
                            <?php echo !empty($row['end_date']) ? $row['end_date'] : ''; ?>
                            <?php echo !empty($row['end_time']) ? $row['end_time'] : ''; ?>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
                
                <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No community events available at the moment. Please check back later.</p>
    <?php endif; ?>
    </section>
</main>

<?php
    include('../footer.php');
    $conn->close();
?>
