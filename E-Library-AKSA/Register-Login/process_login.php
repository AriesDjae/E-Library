<?php
require_once 'auth.php';
require_once __DIR__ . '/db_config.php';
require_once 'SessionManager.php';

// Koneksi ke database
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi validasi input
function validate_input($input)
{
    return htmlspecialchars(trim($input));
}

// Fungsi login
function processLogin($conn, $email_or_username, $password, $userType, $remember)
{
    $table = $userType === 'anggota' ? 'anggota' : 'petugas';
    $idCol = $userType === 'anggota' ? 'ID_Anggota' : 'ID_Petugas';

    $sql = "SELECT * FROM $table WHERE (Email = ? OR Username = ?) AND Status = 'Aktif'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email_or_username, $email_or_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            // Periksa masa aktif
            $lastLogin = $user['Tanggal_Registrasi'];
            if (strtotime($lastLogin) < strtotime('-6 months')) {
                deactivateUser($conn, $user[$idCol], $table);
                return 'account_inactive';
            }

            // Update login status
            updateUserStatus($conn, $user[$idCol], $table);

            // Mulai session dan set cookie
            $sessionManager = new SessionManager($conn);
            $sessionManager->startSession($user, $userType);
            if ($remember) {
                $auth = new Auth($conn);
                $auth->createLoginCookies($user, $userType, true);
            }

            // Redirect
            $redirectPath = $userType === 'anggota' ? 'member/dashboard.php' : 'admin/dashboard.php';
            header("Location: $redirectPath");
            exit();
        }
    }

    return 'invalid_credentials';
}

// Fungsi untuk memperbarui status pengguna
function updateUserStatus($conn, $userID, $table)
{
    $sql = "UPDATE $table SET Status = 'Aktif', Tanggal_Registrasi = NOW() WHERE ID_$table = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
}

// Fungsi untuk menonaktifkan pengguna
function deactivateUser($conn, $userID, $table)
{
    $sql = "UPDATE $table SET Status = 'Nonaktif' WHERE ID_$table = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Simpan hasil filter_input ke dalam variabel
    $email_or_username = filter_input(INPUT_POST, 'email_or_username');
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;

    // Periksa input jika kosong
    if (empty($email_or_username) || empty($password)) {
        header("Location: Login.php?error=empty_fields");
        exit();
    }

    // Tentukan apakah login sebagai petugas atau anggota berdasarkan kata kunci email
    $is_petugas = strpos($email_or_username, '@petugas.id') !== false;

    if ($is_petugas) {
        // Query untuk petugas
        $sql = "SELECT * FROM petugas WHERE Email = ? AND Status = 'Aktif'";
    } else {
        // Query untuk anggota
        $sql = "SELECT * FROM anggota WHERE (Email = ? OR Username = ?) AND Status = 'Aktif'";
    }

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
            // Update status menjadi aktif
            $update_sql = $is_petugas 
                ? "UPDATE petugas SET Status = 'Aktif', Tanggal_Registrasi = NOW() WHERE ID_Petugas = ?"
                : "UPDATE anggota SET Status = 'Aktif', Tanggal_Registrasi = NOW() WHERE ID_Anggota = ?";
            $update_stmt = $conn->prepare($update_sql);
            $userID = $is_petugas ? $user['ID_Petugas'] : $user['ID_Anggota'];
            $update_stmt->bind_param("i", $userID);

            $update_stmt->execute();

            // Log dan sesi
            $auth->logLoginAttempt($email_or_username, $is_petugas ? 'petugas' : 'anggota', $user['ID_Anggota'], 'success');
            $sessionManager->startSession($user, $is_petugas ? 'petugas' : 'anggota');

            if ($remember) {
                $auth->createLoginCookies($user, $is_petugas ? 'petugas' : 'anggota', true);
            }

            // Redirect ke dashboard yang sesuai
            header("Location: " . ($is_petugas ? "admin/dashboard.php" : "member/dashboard.php"));
            exit();
        }
    }

    // Log attempt gagal
    $auth->logLoginAttempt($email_or_username, $is_petugas ? 'petugas' : 'anggota');
    header("Location: Login.php?error=invalid_credentials");
    exit();
}
