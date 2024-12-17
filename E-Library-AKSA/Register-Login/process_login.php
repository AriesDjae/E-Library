<?php
require_once 'Register-Login/auth.php';
require_once 'Register-Login/db.php';
require_once 'Register-Login/SessionManager.php';
require_once 'Register-Login/Login.php';

// Koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Validasi input
function validate_input($input) {
    return htmlspecialchars(trim($input));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_or_username = validate_input($_POST['email_or_username'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']) ? true : false;

    if (empty($email_or_username) || empty($password)) {
        header("Location: Register-Login/Login.php?error=empty_fields");
        exit();
    }

    $is_petugas = strpos($email_or_username, '@petugas.id') !== false;
    $userType = $is_petugas ? 'petugas' : 'anggota';
    $idField = $is_petugas ? 'ID_Petugas' : 'ID_Anggota';

    $sql = $is_petugas
        ? "SELECT * FROM petugas WHERE Email = ? AND Status = 'Aktif'"
        : "SELECT * FROM anggota WHERE (Email = ? OR Username = ?) AND Status = 'Aktif'";
    $stmt = $conn->prepare($sql);

    if ($is_petugas) {
        $stmt->bind_param("s", $email_or_username);
    } else {
        $stmt->bind_param("ss", $email_or_username, $email_or_username);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['Password'])) {
            $auth = new Auth($conn);
            $sessionManager = new SessionManager($conn);

            $userID = $user[$idField];
            $update_sql = "UPDATE $userType SET Status = 'Aktif', Tanggal_Registrasi = NOW() WHERE $idField = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("i", $userID);
            $update_stmt->execute();

            $sessionManager->startSession($user, $userType);
            if ($remember) {
                $auth->createLoginCookies($user, $userType, true);
            }

            $redirectPath = $is_petugas ? '../Dashboard/index.php' : '../Home/home.php';
            header("Location: $redirectPath");
            exit();
        }
    }

    $auth = new Auth($conn);
    $auth->logLoginAttempt($email_or_username, $userType);

    header("Location: Register-Login/Login.php?error=invalid_credentials");
    exit();
}
