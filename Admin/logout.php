<?php
session_start();
session_unset();
session_destroy();
header("Location: /Book/index.php");
exit();
?>
