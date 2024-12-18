<?php
require 'db.php';

// Ambil parameter aksi
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Inisialisasi respons
$response = [];

if ($action === 'booking_chart') {
    // Query data chart booking
    $query = "SELECT Tanggal_Booking, COUNT(*) AS Total_Booking 
              FROM reading_room_booking 
              GROUP BY Tanggal_Booking 
              ORDER BY Tanggal_Booking ASC";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }

} elseif ($action === 'rooms') {
    // Query data ruangan baca
    $query = "SELECT * FROM reading_room";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }

} elseif ($action === 'active_bookings') {
    // Query data pemesanan aktif
    $query = "SELECT 
                booking.ID_Booking, 
                room.Nama_Ruang, 
                booking.Tanggal_Booking, 
                booking.Waktu_Mulai, 
                booking.Waktu_Selesai, 
                booking.Status_Booking
              FROM reading_room_booking booking
              JOIN reading_room room ON booking.ID_Room = room.ID_Room
              WHERE booking.Status_Booking = 'Dipesan'";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
} else {
    $response = ['error' => 'Invalid action'];
}

// Return response sebagai JSON
header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
