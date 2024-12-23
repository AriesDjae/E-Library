<?php
require 'db.php';

header('Content-Type: application/json');

$field = $_POST['field'] ?? '';
$value = $_POST['value'] ?? '';
$response = ['available' => true];

if ($field && $value) {
    $allowed_fields = ['username', 'email'];
    if (!in_array($field, $allowed_fields)) {
        die(json_encode(['available' => false, 'error' => 'Invalid field']));
    }

    // Check both tables
    $sql = "SELECT 1 FROM anggota WHERE $field = ? 
            UNION 
            SELECT 1 FROM petugas WHERE $field = ?";
    
    try {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $value, $value);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $response['available'] = false;
        }
        
        $stmt->close();
    } catch (Exception $e) {
        $response = ['available' => false, 'error' => 'Database error'];
    }
}

$conn->close();
echo json_encode($response);
?>