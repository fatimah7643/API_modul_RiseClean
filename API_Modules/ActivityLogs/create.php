<?php
include_once '../../db.php';

header('Content-Type: application/json');

// Menangkap input dari form
$user_id = $_POST['user_id'] ?? null;
$activity_type = $_POST['activity_type'] ?? null;
$description = $_POST['description'] ?? null;
$ip_address = $_POST['ip_address'] ?? $_SERVER['REMOTE_ADDR'];

// Validasi input minimal agar tidak terjadi error "cannot be null"
if (empty($activity_type)) {
    echo json_encode([
        "status"  => "error",
        "message" => "Activity type tidak boleh kosong"
    ]);
    exit;
}

// Persiapkan Query sesuai skema tabel activity_logs
$stmt = $conn->prepare("
    INSERT INTO activity_logs (user_id, activity_type, description, ip_address)
    VALUES (?, ?, ?, ?)
");

// Bind param: i=integer, s=string, s=string, s=string
$stmt->bind_param("isss", $user_id, $activity_type, $description, $ip_address);

if ($stmt->execute()) {
    echo json_encode([
        "status"  => "success",
        "message" => "Activity log berhasil ditambahkan",
        "data"    => [
            "id" => $stmt->insert_id,
            "user_id" => $user_id,
            "activity_type" => $activity_type,
            "ip_address" => $ip_address
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