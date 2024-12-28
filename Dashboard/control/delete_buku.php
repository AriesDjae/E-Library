<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_buku = $_POST['id_buku'] ?? '';

    if (!empty($id_buku)) {
        $stmt = $conn->prepare("DELETE FROM buku WHERE ID_Buku = ?");
        $stmt->bind_param("i", $id_buku);

        if ($stmt->execute()) {
            header("Location: index.php?message=Data berhasil dihapus");
        } else {
            echo "Terjadi kesalahan: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "ID Buku tidak valid.";
    }
}
$conn->close();
?>
