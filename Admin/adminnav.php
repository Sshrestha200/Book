<!DOCTYPE html>
<html lang="en">
<head>
        <script src="../script.js" defer></script>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .user-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
        }

        .user-menu {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown-content.show {
            display: block;
        }

        
    </style>
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <a href="../index.php"><img src="../Media/icon.png" alt="Book"></a>
                The Book Nook
            </div>
            <button class="menu-toggle" aria-label="Toggle menu">
                <span class="menu-icon">&#9776;</span> 
            </button>
            <ul class="nav-links">
                <li><a href="admindashboard.php">Home</a></li>
                <li><a href="admincontact.php">Contact Us</a></li>
                <li><a href="admincommunity.php">Community & Partnerships</a></li>
                <li><a href="admincollection.php">Our Collection</a></li>
                <li><a href="adminblogs.php">Blogs</a></li>
            </ul>
            <div class="user-menu">
                <img src="../Media/user.png" alt="User Icon" class="user-icon" id="userIcon">
                <div class="dropdown-content" id="userDropdown">
                    <a href="settings.php">Settings</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
        </nav>
        <div class="banner">
            <h1> <img src="../Media/banner.png" alt="Book Icon" class="banner-icon">  Read Your Favourite Book</h1>
        </div>
    </header>
</body>
</html>
<script>
    document.addEventListener("DOMContentLoaded", function() {
    const userIcon = document.getElementById("userIcon");
    const userDropdown = document.getElementById("userDropdown");

    userIcon.addEventListener("click", function() {
        userDropdown.classList.toggle("show");
    });

    window.addEventListener("click", function(event) {
        if (!userIcon.contains(event.target)) {
            userDropdown.classList.remove("show");
        }
    });
});

</script>