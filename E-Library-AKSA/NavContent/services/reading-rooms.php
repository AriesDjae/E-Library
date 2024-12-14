<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "e-library");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengupdate status ruangan ke "Tersedia" jika waktu pemesanan sudah selesai
$currentDate = date('Y-m-d');
$currentTime = date('H:i:s');
$updateQuery = $conn->prepare("UPDATE reading_room 
                SET Status = 'Tersedia' 
                WHERE ID_Room IN (
                    SELECT ID_Room 
                    FROM reading_room_booking 
                    WHERE Tanggal_Booking < ? OR (Tanggal_Booking = ? AND Waktu_Selesai < ?)
                )");
$updateQuery->bind_param("sss", $currentDate, $currentDate, $currentTime);
$updateQuery->execute();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
    $response = ['status' => 'error', 'message' => ''];
    $roomId = filter_input(INPUT_POST, 'roomId', FILTER_VALIDATE_INT);
    $userId = filter_input(INPUT_POST, 'userId', FILTER_VALIDATE_INT);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $startTime = filter_input(INPUT_POST, 'startTime', FILTER_SANITIZE_STRING);
    $endTime = filter_input(INPUT_POST, 'endTime', FILTER_SANITIZE_STRING);

    if (strtotime($date) < strtotime($currentDate)) {
        $response['message'] = "Cannot book for past dates";
    } else {
        $duration = (strtotime($endTime) - strtotime($startTime)) / 60;
        if ($duration < 30 || $duration > 240) {
            $response['message'] = "Booking duration must be between 30 minutes and 4 hours";
        } else {
            $checkQuery = $conn->prepare("SELECT * FROM reading_room_booking 
                           WHERE ID_Room = ? 
                           AND Tanggal_Booking = ? 
                           AND ((Waktu_Mulai BETWEEN ? AND ?) OR 
                                (Waktu_Selesai BETWEEN ? AND ?) OR
                                (? BETWEEN Waktu_Mulai AND Waktu_Selesai))");
            $checkQuery->bind_param("issssss", $roomId, $date, $startTime, $endTime, $startTime, $endTime, $startTime);
            $checkQuery->execute();
            $result = $checkQuery->get_result();

            if ($result->num_rows > 0) {
                $response['message'] = "Sorry, the selected room is already booked during the selected time.";
            } else {
                $insertQuery = $conn->prepare("INSERT INTO reading_room_booking (ID_Room, ID_Anggota, Tanggal_Booking, Waktu_Mulai, Waktu_Selesai)
                                VALUES (?, ?, ?, ?, ?)");
                $insertQuery->bind_param("iisss", $roomId, $userId, $date, $startTime, $endTime);

                if ($insertQuery->execute()) {
                    $updateRoomStatus = $conn->prepare("UPDATE reading_room SET Status = 'Dipesan' WHERE ID_Room = ?");
                    $updateRoomStatus->bind_param("i", $roomId);
                    $updateRoomStatus->execute();

                    $response['status'] = 'success';
                    $response['message'] = "Room successfully booked!";
                } else {
                    $response['message'] = "There was an error processing your booking: " . $conn->error;
                }
            }
        }
    }
    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Reading Room - E-Library</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="content">
        <section class="book-borrowing">
            <h2>Book a Reading Room</h2>
            <p>Reserve a quiet space for focused study or research. Please fill out the form below to book your room:</p>
            
            <form id="bookingForm" class="booking-form" method="post">
                <div class="form-group">
                    <label for="roomId">Select Room:</label>
                    <select id="roomId" name="roomId" required>
                        <?php
                        $query = "SELECT ID_Room, Nama_Ruang FROM reading_room WHERE Status = 'Tersedia'";
                        $result = $conn->query($query);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='{$row['ID_Room']}'>{$row['Nama_Ruang']}</option>";
                            }
                        } else {
                            echo "<option value=''>No rooms available</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="userId">User ID:</label>
                    <input type="text" id="userId" name="userId" required>
                </div>

                <div class="form-group">
                    <label for="date">Booking Date:</label>
                    <input type="date" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="form-group">
                    <label for="startTime">Start Time:</label>
                    <input type="time" id="startTime" name="startTime" required>
                </div>

                <div class="form-group">
                    <label for="endTime">End Time:</label>
                    <input type="time" id="endTime" name="endTime" required>
                </div>

                <button type="submit" name="book" class="btn">Book Room</button>
            </form>

            <div id="notification" class="notification"></div>
        </section>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('bookingForm');
        const notification = document.getElementById('notification');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                notification.textContent = data.message;
                notification.className = `notification ${data.status}`;
            })
            .catch(error => {
                notification.textContent = 'An error occurred. Please try again.';
                notification.className = 'notification error';
            });
        });
    });
    </script>
</body>
</html>

<?php
$conn->close();
?>
