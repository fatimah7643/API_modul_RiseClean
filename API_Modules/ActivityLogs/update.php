<?php
include '../../db.php';

header('Content-Type: application/json');

// Gunakan $_POST jika kamu pakai x-www-form-urlencoded di Postman
$log_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;
$activity_type = $_POST['activity_type'] ?? null;
$description = $_POST['description'] ?? null;
$ip_address = $_POST['ip_address'] ?? null;

// Validasi: Jangan jalankan query jika data penting kosong
if (!$log_id) {
    echo json_encode([
        "status"  => "error",
        "message" => "Log ID harus diisi"
    ]);
    exit;
}

// Siapkan query update
$params = [];
$types = "";

$update_fields = [];

if ($user_id !== null) {
    $update_fields[] = "user_id = ?";
    $params[] = $user_id;
    $types .= "i";
}

if ($activity_type !== null) {
    $update_fields[] = "activity_type = ?";
    $params[] = $activity_type;
    $types .= "s";
}

if ($description !== null) {
    $update_fields[] = "description = ?";
    $params[] = $description;
    $types .= "s";
}

if ($ip_address !== null) {
    $update_fields[] = "ip_address = ?";
    $params[] = $ip_address;
    $types .= "s";
}

if (!empty($update_fields)) {
    $sql = "UPDATE activity_logs SET " . implode(", ", $update_fields) . " WHERE id = ?";
    $params[] = $log_id;
    $types .= "i";

    try {
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode([
                        "status"  => "success",
                        "message" => "Data activity log berhasil diperbarui",
                        "data"    => [
                            "id" => $log_id,
                            "user_id" => $user_id,
                            "activity_type" => $activity_type,
                            "ip_address" => $ip_address
                        ]
                    ]);
                } else {
                    echo json_encode([
                        "status"  => "error",
                        "message" => "Tidak ada data yang diubah"
                    ]);
                }
            } else {
                throw new Exception($stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception("Prepare failed: " . $conn->error);
        }

    } catch (Exception $e) {
        echo json_encode([
            "status"  => "error",
            "message" => "Gagal update: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status"  => "error",
        "message" => "Tidak ada data yang diperbarui"
    ]);
}

$conn->close();
?>