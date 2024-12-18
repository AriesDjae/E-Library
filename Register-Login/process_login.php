<?php
require 'db.php';
require 'SessionManager.php';

// Validasi input
function validate_input($input) {
    return htmlspecialchars(trim($input));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validasi input dari form
    $email = validate_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']) ? true : false;

    // Periksa input kosong
    if (empty($email) || empty($password)) {
        header("Location: Register-Login/Login.php?error=empty_fields");
        exit();
    }

    // Periksa apakah email mengandung '@petugas.id'
    $is_petugas = strpos($email, '@petugas.id') !== false; 
    $userType = $is_petugas ? 'petugas' : 'anggota';
    $idField = $is_petugas ? 'ID_Petugas' : 'ID_Anggota';

    // Query berdasarkan jenis pengguna
    $sql = $is_petugas
        ? "SELECT * FROM petugas WHERE Email = ? AND Status = 'Aktif'"
        : "SELECT * FROM anggota WHERE Email = ? AND Status = 'Aktif'";
    
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $email);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Jika ditemukan user dalam database
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['Password'])) {
            // Jika password cocok
            session_start();
            $_SESSION['user_id'] = $user[$idField];
            $_SESSION['user_type'] = $userType;

            if ($remember) {
                setcookie("remember_user", $email, time() + (86400 * 30), "/"); // 30 hari
            }

            // Redirect berdasarkan tipe pengguna
            if ($is_petugas) {
                header("Location: ../Dashboard/index.php");
            } else {
                header("Location: ../Home/index.php");
            }
            exit();
        }
    }

    // Jika kredensial salah
    header("Location: Register-Login/Login.php?error=invalid_credentials");
    exit();
}
?>
