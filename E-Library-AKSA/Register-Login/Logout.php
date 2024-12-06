<?php
session_start();

// Hapus semua session
session_destroy();

// Hapus cookies
setcookie("user_email", "", time() - 3600, "/");
setcookie("user_id", "", time() - 3600, "/");

// Redirect ke halaman login
header("Location: Login.php");
exit();
?>