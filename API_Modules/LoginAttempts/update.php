<?php
include '../../db.php';

header('Content-Type: application/json');

// Gunakan $_POST jika kamu pakai x-www-form-urlencoded di Postman
$attempt_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$ip_address = $_POST['ip_address'] ?? null;
$attempt_time = $_POST['attempt_time'] ?? null;
$is_success = isset($_POST['is_success']) ? (int)$_POST['is_success'] : null;

// Validasi: Jangan jalankan query jika data penting kosong
if (!$attempt_id) {
    echo json_encode([
        "status"  => "error",
        "message" => "Attempt ID harus diisi"
    ]);
    exit;
}

// Siapkan query update
$params = [];
$types = "";

$update_fields = [];

if ($ip_address !== null) {
    $update_fields[] = "ip_address = ?";
    $params[] = $ip_address;
    $types .= "s";
}

if ($attempt_time !== null) {
    $update_fields[] = "attempt_time = ?";
    $params[] = $attempt_time;
    $types .= "s";
}

if ($is_success !== null) {
    $update_fields[] = "is_success = ?";
    $params[] = $is_success;
    $types .= "i";
}

if (!empty($update_fields)) {
    $sql = "UPDATE login_attempts SET " . implode(", ", $update_fields) . " WHERE attempt_id = ?";
    $params[] = $attempt_id;
    $types .= "i";

    try {
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode([
                        "status"  => "success",
                        "message" => "Data login attempt berhasil diperbarui",
                        "data"    => [
                            "attempt_id" => $attempt_id,
                            "ip_address" => $ip_address,
                            "is_success" => $is_success
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