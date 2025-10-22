<?php
// filepath: c:\wamp64\www\KAMA\logout.php
session_start();
session_unset();
session_destroy();
header("Location: index.php");
exit();
?>