<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Reading Room - E-Library</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="content">
        <section class="reading-room-booking">
            <h2>Book a Reading Room</h2>
            <p>Reserve a quiet space for focused study or research. Please fill out the form below to book your room:</p>
            <form action="" method="post">
                <div class="form-group">
                    <label for="roomId">Room ID:</label>
                    <input type="text" id="roomId" name="roomId" required>
                </div>
                <div class="form-group">
                    <label for="userId">User ID:</label>
                    <input type="text" id="userId" name="userId" required>
                </div>
                <div class="form-group">
                    <label for="date">Booking Date:</label>
                    <input type="date" id="date" name="date" required>
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
            <?php
            // Proses Pemesanan Ruangan
            if (isset($_POST['book'])) {
                $roomId = htmlspecialchars($_POST['roomId']);
                $userId = htmlspecialchars($_POST['userId']);
                $date = htmlspecialchars($_POST['date']);
                $startTime = htmlspecialchars($_POST['startTime']);
                $endTime = htmlspecialchars($_POST['endTime']);

                // Tampilkan pesan konfirmasi
                echo "<div class='confirmation'>
                        <p>Room <strong>$roomId</strong> has been successfully booked by User <strong>$userId</strong> on <strong>$date</strong> from <strong>$startTime</strong> to <strong>$endTime</strong>.</p>
                      </div>";
            }
            ?>
        </section>
    </div>
</body>
</html>
