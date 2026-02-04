<?php
include '../../db.php';

header('Content-Type: application/json');

// Gunakan $_POST jika kamu pakai x-www-form-urlencoded di Postman
$user_reward_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;
$reward_id = isset($_POST['reward_id']) ? (int)$_POST['reward_id'] : null;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : null;

// Validasi: Jangan jalankan query jika data penting kosong
if (!$user_reward_id) {
    echo json_encode([
        "status"  => "error",
        "message" => "User Reward ID harus diisi"
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

if ($reward_id !== null) {
    $update_fields[] = "reward_id = ?";
    $params[] = $reward_id;
    $types .= "i";
}

if ($quantity !== null) {
    $update_fields[] = "quantity = ?";
    $params[] = $quantity;
    $types .= "i";
}

if (!empty($update_fields)) {
    $sql = "UPDATE user_rewards SET " . implode(", ", $update_fields) . " WHERE user_reward_id = ?";
    $params[] = $user_reward_id;
    $types .= "i";

    try {
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode([
                        "status"  => "success",
                        "message" => "Data user reward berhasil diperbarui",
                        "data"    => [
                            "user_reward_id" => $user_reward_id,
                            "user_id" => $user_id,
                            "reward_id" => $reward_id,
                            "quantity" => $quantity
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