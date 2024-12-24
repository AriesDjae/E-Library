<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "e-library");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to update room status to 'Available' if the booking time has passed
function updateRoomStatus($conn) {
    $currentDate = date('Y-m-d');
    $currentTime = date('H:i:s');

    // Query to update status to 'Tersedia' if the booking time has passed
    $updateQuery = $conn->prepare("UPDATE reading_room SET Status = 'Tersedia' WHERE ID_Room IN (
                                    SELECT ID_Room FROM reading_room_booking
                                    WHERE (Tanggal_Booking < ? OR (Tanggal_Booking = ? AND Waktu_Selesai < ?))
                                  )");
    $updateQuery->bind_param("sss", $currentDate, $currentDate, $currentTime);
    $updateQuery->execute();
}

// Function to validate booking duration
function validateBookingDuration($startTime, $endTime) {
    $duration = (strtotime($endTime) - strtotime($startTime)) / 60; // Calculate duration in minutes
    return $duration >= 30 && $duration <= 240; // Must be between 30 minutes and 4 hours
}

$response = ['status' => '', 'message' => ''];

// Update room statuses periodically
updateRoomStatus($conn);

// Handle AJAX request for booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'book') {
    $roomId = filter_input(INPUT_POST, 'roomId', FILTER_VALIDATE_INT);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $startTime = filter_input(INPUT_POST, 'startTime', FILTER_SANITIZE_STRING);
    $endTime = filter_input(INPUT_POST, 'endTime', FILTER_SANITIZE_STRING);

    // Check if inputs are valid
    if (!$roomId || !$username || !$date || !$startTime || !$endTime) {
        $response['message'] = "Invalid input. Please check your input values.";
    } else {
        // Ensure the date is not in the past
        if (strtotime($date) < strtotime(date('Y-m-d'))) {
            $response['message'] = "Cannot book for a past date.";
        } elseif (!validateBookingDuration($startTime, $endTime)) {
            // Validate booking duration
            $response['message'] = "Booking duration must be between 30 minutes and 4 hours.";
        } else {
            // Check if the room is available at the selected time
            $checkQuery = $conn->prepare("SELECT * FROM reading_room_booking
                                         WHERE ID_Room = ? AND Tanggal_Booking = ? 
                                         AND ((Waktu_Mulai BETWEEN ? AND ?) OR 
                                              (Waktu_Selesai BETWEEN ? AND ?) OR
                                              (? BETWEEN Waktu_Mulai AND Waktu_Selesai))");
            $checkQuery->bind_param("issssss", $roomId, $date, $startTime, $endTime, $startTime, $endTime, $startTime);
            $checkQuery->execute();
            $result = $checkQuery->get_result();

            if ($result->num_rows > 0) {
                $response['message'] = "Sorry, the room is already booked for the selected time.";
            } else {
                // Insert the booking into the database
                $insertQuery = $conn->prepare("INSERT INTO reading_room_booking (ID_Room, Username, Tanggal_Booking, Waktu_Mulai, Waktu_Selesai)
                                               VALUES (?, ?, ?, ?, ?)");
                $insertQuery->bind_param("issss", $roomId, $username, $date, $startTime, $endTime);

                if ($insertQuery->execute()) {
                    // Update room status to 'Booked'
                    $updateRoomStatus = $conn->prepare("UPDATE reading_room SET Status = 'Dipesan' WHERE ID_Room = ?");
                    $updateRoomStatus->bind_param("i", $roomId);
                    $updateRoomStatus->execute();

                    $response['status'] = 'success';
                    $response['message'] = "Room successfully booked!";
                } else {
                    $response['message'] = "An error occurred while processing the booking. Please try again.";
                }
            }
        }
    }

    echo json_encode($response);
    $conn->close();
    exit;
}

// Fetch available rooms
$roomQuery = "SELECT ID_Room, Nama_Ruang FROM reading_room WHERE Status = 'Tersedia'";
$roomsResult = $conn->query($roomQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reading Room Reservation</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f7fafc;
            display: flex;
            justify-content: center;
            height: 100vh;
            margin: 0;
            margin-top: 80px;
            box-sizing: border-box;
        }

        .form-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 600px;
            box-sizing: border-box;
        }

        label {
            font-weight: 500;
            margin-bottom: 5px;
            display: block;
        }

        input, select, button {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-top: 8px;
            font-size: 14px;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2980b9;
        }

        .notification {
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            display: none;
            text-align: center;
        }

        .container {
            width: auto !important;
        }
        
        .success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }

    </style>
</head>
<body>

    <div class="form-container">
        <h2>Reading Room Reservation</h2>

        <form id="roomBookingForm">
            <label for="roomId">Select Room:</label>
            <select id="roomId" name="roomId" required>
                <option value="">Select Room</option>
                <?php while ($room = $roomsResult->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($room['ID_Room']); ?>"><?= htmlspecialchars($room['Nama_Ruang']); ?></option>
                <?php endwhile; ?>
            </select>

            <label for="username">Member Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="date">Booking Date:</label>
            <input type="date" id="date" name="date" required>

            <label for="startTime">Start Time:</label>
            <input type="time" id="startTime" name="startTime" required>

            <label for="endTime">End Time:</label>
            <input type="time" id="endTime" name="endTime" required>

            <button type="submit">Book Room</button>
        </form>

        <div id="notification" class="notification"></div>
    </div>

    <script>
        document.getElementById('roomBookingForm').addEventListener('submit', async function(event) {
            event.preventDefault();

            const formData = new FormData(this);
            formData.append('action', 'book');

            try {
                const response = await fetch('reading-rooms.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                const notification = document.getElementById('notification');
                if (result.status === 'success') {
                    notification.textContent = result.message;
                    notification.classList.remove('error');
                    notification.classList.add('success');
                } else {
                    notification.textContent = result.message;
                    notification.classList.remove('success');
                    notification.classList.add('error');
                }

                notification.style.display = 'block';
            } catch (error) {
                console.error('Error:', error);
                const notification = document.getElementById('notification');
                notification.textContent = 'An error occurred. Please try again later.';
                notification.classList.remove('success');
                notification.classList.add('error');
                notification.style.display = 'block';
            }
        });
    </script>

</body>
</html>

<?php
// Close connection
$conn->close();
?>
