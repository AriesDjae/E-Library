<?php
class Auth {
    private $conn;
    private $cookie_expiry = 2592000; // 30 hari dalam detik
    
    public function __construct($db_connection) {
        $this->conn = $db_connection;
    }
    
    public function createLoginCookies($user_data, $user_type, $remember = false) {
        $token = bin2hex(random_bytes(32));
        $id_field = 'ID_' . ucfirst($user_type);
        $user_id = $user_data[$id_field];
        
        // Hash token untuk keamanan
        $token_hash = hash('sha256', $token);
        $expiry = $remember ? time() + $this->cookie_expiry : 0;
        
        // Simpan token ke database
        $sql = "INSERT INTO auth_tokens (user_type, user_id, token_hash, expiry) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log("Kesalahan dalam persiapan statement: " . $this->conn->error);
            return false;
        }
        $stmt->bind_param("siss", $user_type, $user_id, $token_hash, date('Y-m-d H:i:s', $expiry));
        if (!$stmt->execute()) {
            error_log("Kesalahan saat mengeksekusi query: " . $stmt->error);
            return false;
        }
        
        // Set cookies
        if ($remember) {
            setcookie("auth_token", $token, $expiry, "/", "", true, true);
            setcookie("user_type", $user_type, $expiry, "/", "", true, true);
            setcookie("user_id", $user_id, $expiry, "/", "", true, true);
            setcookie("user_email", $user_data['Email'], $expiry, "/", "", true, true);
            setcookie("user_name", $user_type === 'petugas' ? $user_data['Nama_Petugas'] : $user_data['Nama'], $expiry, "/", "", true, true);
        }
        
        // Set session
        session_regenerate_id(true); // Regenerasi ID sesi
        $_SESSION['user_type'] = $user_type;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['email'] = $user_data['Email'];
        $_SESSION['nama'] = $user_type === 'petugas' ? $user_data['Nama_Petugas'] : $user_data['Nama'];
        $_SESSION['status'] = $user_data['Status'];
        $_SESSION['last_activity'] = time();
        return true;
    }
    
    public function validateAuthCookie() {
        if (isset($_COOKIE['auth_token']) && isset($_COOKIE['user_type']) && isset($_COOKIE['user_id'])) {
            $token = $_COOKIE['auth_token'];
            $user_type = $_COOKIE['user_type'];
            $user_id = $_COOKIE['user_id'];
            $token_hash = hash('sha256', $token);
            
            // Cek token di database
            $sql = "SELECT id FROM auth_tokens WHERE user_type = ? AND user_id = ? AND token_hash = ? AND expiry > NOW() AND is_valid = 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sis", $user_type, $user_id, $token_hash);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                // Token valid, perbarui session
                session_regenerate_id(true); // Regenerasi ID sesi
                return true;
            }
        }
        return false;
    }
    
    public function clearAuthCookies() {
        if (isset($_COOKIE['auth_token']) && isset($_COOKIE['user_type']) && isset($_COOKIE['user_id'])) {
            $token = $_COOKIE['auth_token'];
            $user_type = $_COOKIE['user_type'];
            $user_id = $_COOKIE['user_id'];
            $token_hash = hash('sha256', $token);
            
            // Invalidate token in database
            $sql = "UPDATE auth_tokens SET is_valid = 0 WHERE user_type = ? AND user_id = ? AND token_hash = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sis", $user_type, $user_id, $token_hash);
            $stmt->execute();
        }
        
        // Hapus cookies
        setcookie("auth_token", "", time() - 3600, "/");
        setcookie("user_type", "", time() - 3600, "/");
        setcookie("user_id", "", time() - 3600, "/");
        setcookie("user_email", "", time() - 3600, "/");
        setcookie("user_name", "", time() - 3600, "/");
    }
    
    public function logLoginAttempt($email, $user_type, $user_id = null, $status = 'failed') {
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        
        $sql = "INSERT INTO login_logs (user_type, user_id, email, ip_address, user_agent, status) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sisss", $user_type, $user_id, $email, $ip_address, $user_agent, $status);
        $stmt->execute();
    }
}
?>
