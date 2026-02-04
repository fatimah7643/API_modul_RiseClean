<?php
include_once '../../db.php';

header('Content-Type: application/json');

// Menangkap input dari form
$user_id = $_POST['user_id'] ?? null;
$item_id = $_POST['item_id'] ?? null;
$item_type = $_POST['item_type'] ?? null; // 'module' or 'challenge'
$completed_at = $_POST['completed_at'] ?? date('Y-m-d H:i:s');
$verified_at = $_POST['verified_at'] ?? null;
$is_verified = $_POST['is_verified'] ?? 0;
$submission_text = $_POST['submission_text'] ?? null;
$submission_image = $_POST['submission_image'] ?? null;

// Validasi input minimal agar tidak terjadi error "cannot be null"
if (!$user_id || !$item_id || !$item_type) {
    echo json_encode([
        "status"  => "error",
        "message" => "User ID, Item ID, dan Item Type tidak boleh kosong"
    ]);
    exit;
}

// Persiapkan Query sesuai skema tabel user_progress
$stmt = $conn->prepare("
    INSERT INTO user_progress (user_id, item_id, item_type, completed_at, verified_at, is_verified, submission_text, submission_image)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

// Bind param: i=integer, i=integer, s=string, s=string, s=string, i=integer, s=string, s=string
$stmt->bind_param("iisssiss", $user_id, $item_id, $item_type, $completed_at, $verified_at, $is_verified, $submission_text, $submission_image);

if ($stmt->execute()) {
    echo json_encode([
        "status"  => "success",
        "message" => "User progress berhasil ditambahkan",
        "data"    => [
            "progress_id" => $stmt->insert_id,
            "user_id" => $user_id,
            "item_id" => $item_id,
            "item_type" => $item_type,
            "is_verified" => $is_verified
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