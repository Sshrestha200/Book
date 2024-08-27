<?php

include('db_connect.php');

// Fetch all books from the database
$result = $conn->query("SELECT * FROM books");

$books = [];
while($row = $result->fetch_assoc()) {
    $books[] = [
        'title' => htmlspecialchars($row['title']),
        'category' => htmlspecialchars($row['genre']),
        'author' => htmlspecialchars($row['writer']),
        'description' => htmlspecialchars($row['description']),
        'image' => 'uploads/images/' . htmlspecialchars($row['cover_image']),
        'pdf' => 'uploads/pdfs/' . htmlspecialchars($row['pdf_file']),
    ];
}

$conn->close();
?>

<div class="content">
<main>
    <h1>Book Collection</h1>
    
    <!-- Search Input -->
    <input type="text" id="search" placeholder="Search Books" style="margin-bottom: 20px; padding: 10px; width: 100%; max-width: 400px; border-radius: 5px; border: 1px solid #ccc;">
    
    <!-- Book List Container -->
    <div id="book-list" class="book-list">
        <!-- Books will be dynamically loaded here by JavaScript -->
    </div>
</main></div>

<?php include('footer.php'); ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Books array populated from PHP
        const books = <?php echo json_encode($books); ?>;

        const bookList = document.getElementById('book-list');
        const searchInput = document.getElementById('search');

        function displayBooks(books) {
            bookList.innerHTML = '';
            books.forEach(book => {
                const bookCard = document.createElement('div');
                bookCard.classList.add('book-card');
                bookCard.innerHTML = `
                    <img src="${book.image}" alt="${book.title}">
                    <h4>${book.title}</h4>
                    <p><strong>Author:</strong> ${book.author}</p>
                    <p><strong>Category:</strong> ${book.category}</p>
                    <p>${book.description}</p>
                    <a href="${book.pdf}" target="_blank" class="card-link">Download PDF</a>
                `;
                bookList.appendChild(bookCard);
            });
        }

        // Initial display of books
        if (bookList) { 
            displayBooks(books);

            // Search books
            searchInput?.addEventListener('input', function() {
                const searchTerm = searchInput.value.toLowerCase();
                const filteredBooks = books.filter(book => book.title.toLowerCase().includes(searchTerm));
                displayBooks(filteredBooks);
            });
        }
    });
</script>

