<?php
class SessionManager {
    private $conn;
    private $session_lifetime = 7200; // 2 jam dalam detik

    public function __construct($db_connection) {
        $this->conn = $db_connection;

        // Atur handler session
        session_set_save_handler(
            [$this, 'open'],
            [$this, 'close'],
            [$this, 'read'],
            [$this, 'write'],
            [$this, 'destroy'],
            [$this, 'gc']
        );

        // Mulai session secara otomatis jika belum dimulai
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function startSession($user_data, $user_type) {
        session_regenerate_id(true); // Hindari session fixation
        $session_id = session_id();
        $user_id = $user_data['ID_' . ucfirst($user_type)];
        
        // Simpan session ke database
        $sql = "REPLACE INTO user_sessions (user_type, user_id, session_id, ip_address, user_agent, last_activity) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            die("Kesalahan dalam persiapan statement: " . $this->conn->error);
        }
        $stmt->bind_param(
            "sisss", 
            $user_type, 
            $user_id, 
            $session_id, 
            $_SERVER['REMOTE_ADDR'],
            $_SERVER['HTTP_USER_AGENT']
        );
        if (!$stmt->execute()) {
            die("Kesalahan saat mengeksekusi query: " . $stmt->error);
        }
        
        // Set session variables
        $_SESSION['user_type'] = $user_type;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['email'] = $user_data['Email'];
        $_SESSION['nama'] = $user_type === 'petugas' ? $user_data['Nama_Petugas'] : $user_data['Nama'];
        $_SESSION['status'] = $user_data['Status'];
        $_SESSION['last_activity'] = time();
    }

    public function validateSession() {
        if (isset($_SESSION['user_id'], $_SESSION['user_type'])) {
            $sql = "SELECT * FROM user_sessions 
                    WHERE user_type = ? AND user_id = ? AND session_id = ?";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                return false;
            }
            $stmt->bind_param("sis", 
                $_SESSION['user_type'], 
                $_SESSION['user_id'], 
                session_id()
            );
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Perbarui aktivitas terakhir
                $this->updateLastActivity(session_id());
                return true;
            }
        }
        return false;
    }

    private function updateLastActivity($session_id) {
        $sql = "UPDATE user_sessions SET last_activity = NOW() WHERE session_id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            die("Kesalahan saat memperbarui aktivitas terakhir: " . $this->conn->error);
        }
        $stmt->bind_param("s", $session_id);
        $stmt->execute();
    }

    public function destroySession() {
        $sql = "DELETE FROM user_sessions WHERE session_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", session_id());
        $stmt->execute();

        // Hapus cookie session
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Hapus semua data session
        $_SESSION = [];
        session_destroy();
    }

    public function gc($maxlifetime) {
        $sql = "DELETE FROM user_sessions WHERE last_activity < NOW() - INTERVAL ? SECOND";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            die("Kesalahan saat melakukan garbage collection: " . $this->conn->error);
        }
        $stmt->bind_param("i", $maxlifetime);
        $stmt->execute();
        return true;
    }

    public function open() {
        // Tidak diperlukan tindakan khusus saat membuka session
        return true;
    }

    public function close() {
        // Tidak diperlukan tindakan khusus saat menutup session
        return true;
    }

    public function read($session_id) {
        $sql = "SELECT session_data FROM user_sessions WHERE session_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $session_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return $row['session_data'] ?? '';
        }
        return '';
    }

    public function write($session_id, $data) {
        $sql = "REPLACE INTO user_sessions (session_id, session_data, last_activity) 
                VALUES (?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $session_id, $data);
        return $stmt->execute();
    }
}
?>
