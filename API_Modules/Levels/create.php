<?php
include_once '../../db.php';

header('Content-Type: application/json');

// Menangkap input dari form
$level_name = $_POST['level_name'] ?? null;
$min_xp = $_POST['min_xp'] ?? 0;

// Validasi input minimal agar tidak terjadi error "cannot be null"
if (empty($level_name)) {
    echo json_encode([
        "status"  => "error",
        "message" => "Level name tidak boleh kosong"
    ]);
    exit;
}

// Persiapkan Query sesuai skema tabel levels
$stmt = $conn->prepare("
    INSERT INTO levels (level_name, min_xp)
    VALUES (?, ?)
");

// Bind param: s=string, i=integer
$stmt->bind_param("si", $level_name, $min_xp);

if ($stmt->execute()) {
    echo json_encode([
        "status"  => "success",
        "message" => "Level berhasil ditambahkan",
        "data"    => [
            "level_id" => $stmt->insert_id,
            "level_name" => $level_name,
            "min_xp" => $min_xp
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