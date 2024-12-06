<?php
class SessionManager {
    private $conn;
    private $session_lifetime = 7200; // 2 jam dalam detik

    public function __construct($db_connection) {
        $this->conn = $db_connection;
        session_set_save_handler(
            array($this, 'open'),
            array($this, 'close'),
            array($this, 'read'),
            array($this, 'write'),
            array($this, 'destroy'),
            array($this, 'gc')
        );
    }

    public function startSession($user_data, $user_type) {
        session_start();
        $session_id = session_id();
        $user_id = $user_data['ID_' . ucfirst($user_type)];
        
        // Simpan session ke database
        $sql = "INSERT INTO user_sessions (user_type, user_id, session_id, ip_address, user_agent) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sisss", 
            $user_type, 
            $user_id, 
            $session_id, 
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['HTTP_USER_AGENT']
        );
        $stmt->execute();
        
        // Set session variables
        $_SESSION['user_type'] = $user_type;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['email'] = $user_data['Email'];
        $_SESSION['nama'] = $user_type === 'petugas' ? $user_data['Nama_Petugas'] : $user_data['Nama'];
        $_SESSION['status'] = $user_data['Status'];
        $_SESSION['last_activity'] = time();
    }

    public function validateSession() {
        if (isset($_SESSION['user_id']) && isset($_SESSION['user_type'])) {
            $sql = "SELECT * FROM user_sessions 
                    WHERE user_type = ? AND user_id = ? AND session_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sis", 
                $_SESSION['user_type'], 
                $_SESSION['user_id'], 
                session_id()
            );
            $stmt->execute();
            return $stmt->get_result()->num_rows > 0;
        }
        return false;
    }

    public function destroySession() {
        // Hapus dari database
        $sql = "DELETE FROM user_sessions WHERE session_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", session_id());
        $stmt->execute();
        
        // Hapus cookie session
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-3600, '/');
        }
        
        // Hapus semua data session
        $_SESSION = array();
        session_destroy();
    }

    // Garbage collection untuk session kadaluarsa
    public function gc($maxlifetime) {
        $old = date('Y-m-d H:i:s', time() - $maxlifetime);
        $sql = "DELETE FROM user_sessions WHERE last_activity < ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $old);
        $stmt->execute();
        return true;
    }
}
?> 