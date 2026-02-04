<?php
include_once '../../db.php';

header('Content-Type: application/json');

// Menangkap input dari form
$user_id = $_POST['user_id'] ?? null;
$reward_id = $_POST['reward_id'] ?? null;
$quantity = $_POST['quantity'] ?? 1;

// Validasi input minimal agar tidak terjadi error "cannot be null"
if (!$user_id || !$reward_id) {
    echo json_encode([
        "status"  => "error",
        "message" => "User ID dan Reward ID tidak boleh kosong"
    ]);
    exit;
}

// Persiapkan Query sesuai skema tabel user_rewards
$stmt = $conn->prepare("
    INSERT INTO user_rewards (user_id, reward_id, quantity)
    VALUES (?, ?, ?)
");

// Bind param: i=integer, i=integer, i=integer
$stmt->bind_param("iii", $user_id, $reward_id, $quantity);

if ($stmt->execute()) {
    echo json_encode([
        "status"  => "success",
        "message" => "User reward berhasil ditambahkan",
        "data"    => [
            "user_reward_id" => $stmt->insert_id,
            "user_id" => $user_id,
            "reward_id" => $reward_id,
            "quantity" => $quantity
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