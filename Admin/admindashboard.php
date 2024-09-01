
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<?php

    include('adminheader.php');
    include('adminnav.php');
    include('../db_connect.php');
    
?>
    <link rel="stylesheet" href="../index.css">
<div class="content">
<?php
    include('adminhome.php');
?></div>

<?php
    include('../footer.php');
?>
