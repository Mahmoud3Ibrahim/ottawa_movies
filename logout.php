<!-- logout.php -->
<?php
include 'config.php';

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to index.php
header("Location: index.php");
exit();
?>