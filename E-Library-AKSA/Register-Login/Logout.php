<?php
session_start();

// Hapus semua session
session_destroy();

// Hapus cookies
setcookie(name: "user_email", value: "", expires_or_options: time() - 3600, path: "/");
setcookie(name: "user_id", value: "", expires_or_options: time() - 3600, path: "/");

// Redirect ke halaman login
header(header: "Location: Login.php");
exit();
?>