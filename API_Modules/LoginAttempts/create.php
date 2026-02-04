<?php
include_once '../../db.php';

header('Content-Type: application/json');

// Menangkap input dari form
$ip_address = $_POST['ip_address'] ?? $_SERVER['REMOTE_ADDR'];
$attempt_time = $_POST['attempt_time'] ?? date('Y-m-d H:i:s');
$is_success = $_POST['is_success'] ?? 0;

// Validasi input minimal agar tidak terjadi error "cannot be null"
if (empty($ip_address)) {
    echo json_encode([
        "status"  => "error",
        "message" => "IP Address tidak boleh kosong"
    ]);
    exit;
}

// Persiapkan Query sesuai skema tabel login_attempts
$stmt = $conn->prepare("
    INSERT INTO login_attempts (ip_address, attempt_time, is_success)
    VALUES (?, ?, ?)
");

// Bind param: s=string, s=string, i=integer
$stmt->bind_param("ssi", $ip_address, $attempt_time, $is_success);

if ($stmt->execute()) {
    echo json_encode([
        "status"  => "success",
        "message" => "Login attempt berhasil ditambahkan",
        "data"    => [
            "attempt_id" => $stmt->insert_id,
            "ip_address" => $ip_address,
            "is_success" => $is_success
        ]
    ]);
} else {
    echo json_encode([
        "status"  => "error",
        "message" => $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>