<?php
include '../../db.php';

header('Content-Type: application/json');

// Menangkap ID dari Postman
$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode([
        "status"  => "error",
        "message" => "ID User Progress tidak ditemukan"
    ]);
    exit;
}

// Query untuk menghapus user progress
$stmt = $conn->prepare("DELETE FROM user_progress WHERE progress_id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode([
            "status"  => "success",
            "message" => "User progress berhasil dihapus",
        ]);
    } else {
        echo json_encode([
            "status"  => "error",
            "message" => "Data tidak ditemukan, gagal menghapus",
        ]);
    }
} else {
    echo json_encode([
        "status"  => "error",
        "message" => $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>