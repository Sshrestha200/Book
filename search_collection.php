<?php
include('db_connect.php');

$search_keyword = "";
if (isset($_GET['search'])) {
    $search_keyword = $conn->real_escape_string($_GET['search']);
}

$query = "SELECT * FROM collection WHERE title LIKE '%$search_keyword%' OR author LIKE '%$search_keyword%'";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="book-list">';
        echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
        echo '<p><strong>Category:</strong> ' . htmlspecialchars($row['category']) . '</p>';
        echo '<p><strong>Author:</strong> ' . htmlspecialchars($row['author']) . '</p>';
        echo '</div>';
    }
} else {
    echo '<p>No collections found matching your search criteria.</p>';
}

$conn->close();
?>
