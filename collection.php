<?php
    include('header.php');
    include('nav.php');
    include('db_connect.php');
     // Initialize variables
     $search_keyword = "";

     // Handle the search query if it's submitted
     if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['search'])) {
         $search_keyword = $conn->real_escape_string($_GET['search']);
         $query = "SELECT * FROM collection WHERE title LIKE '%$search_keyword%' OR author LIKE '%$search_keyword%'";
     } else {
         // Default query to fetch all collections
         $query = "SELECT * FROM collection";
     }
 
     // Execute the query
     $result = $conn->query($query);
?>


<div class="content">
    <?php
    include('Admin/addbooks.php');
?>
<?php
    include('display_books.php');
?>

</div>

<!-- <div class="content">
    <h1>Collection</h1>
   
    <main>
            <div class="container">
                
                <section class="book-section">
                    <input type="text" id="search" placeholder="Search Books">
                    <div id="book-list" class="book-list">

                    </div>
                </section>
            </div>
        </main>
</div> -->


<script>
    document.getElementById('search').addEventListener('input', function() {
        var searchQuery = this.value;

        // Create an AJAX request
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'search_collection.php?search=' + encodeURIComponent(searchQuery), true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Update the collection grid with the results
                document.getElementById('book-list').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    });
</script>