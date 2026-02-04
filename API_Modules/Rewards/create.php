<?php
include_once '../../db.php';

header('Content-Type: application/json');

// Menangkap input dari form
$reward_name = $_POST['reward_name'] ?? null;
$point_cost = $_POST['point_cost'] ?? null;
$description = $_POST['description'] ?? null;
$image = $_POST['image'] ?? null;
$stock = $_POST['stock'] ?? 0;
$is_active = $_POST['is_active'] ?? 1;

// Validasi input minimal agar tidak terjadi error "cannot be null"
if (empty($reward_name) || $point_cost === null) {
    echo json_encode([
        "status"  => "error",
        "message" => "Reward name dan point cost tidak boleh kosong"
    ]);
    exit;
}

// Persiapkan Query sesuai skema tabel rewards
$stmt = $conn->prepare("
    INSERT INTO rewards (reward_name, point_cost, description, image, stock, is_active)
    VALUES (?, ?, ?, ?, ?, ?)
");

// Bind param: s=string, i=integer, s=string, s=string, i=integer, i=integer
$stmt->bind_param("sisiii", $reward_name, $point_cost, $description, $image, $stock, $is_active);

if ($stmt->execute()) {
    echo json_encode([
        "status"  => "success",
        "message" => "Reward berhasil ditambahkan",
        "data"    => [
            "reward_id" => $stmt->insert_id,
            "reward_name" => $reward_name,
            "point_cost" => $point_cost,
            "is_active" => $is_active
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