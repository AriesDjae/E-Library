<?php
require_once 'auth.php';
require_once 'db_config.php';
require_once 'SessionManager.php';
// Koneksi ke database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Lanjutkan dengan proses login
$auth = new Auth($conn);
$sessionManager = new SessionManager($conn);

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;
    $user_type = $_POST['user_type'];
    
    // Validasi login
    $sql = "SELECT * FROM $user_type WHERE Email = ? AND Status = 'Aktif'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            // Log successful login
            $auth->logLoginAttempt($email, $user_type, $user['ID_' . ucfirst($user_type)], 'success');
            
            // Mulai session
            $sessionManager->startSession($user, $user_type);
            
            // Set cookies jika remember me dicentang
            if ($remember) {
                $auth->createLoginCookies($user, $user_type, true);
            }
            
            // Redirect
            header("Location: " . ($user_type === 'petugas' ? 'admin/dashboard.php' : 'member/dashboard.php'));
            exit();
        }
    }
    
    // Log failed attempt
    $auth->logLoginAttempt($email, $user_type);
    header("Location: Login.php?error=1");
    exit();
}
?>
