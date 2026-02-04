<?php
include '../../db.php';

header('Content-Type: application/json');

// Gunakan $_POST jika kamu pakai x-www-form-urlencoded di Postman
$reward_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$reward_name = $_POST['reward_name'] ?? null;
$point_cost = isset($_POST['point_cost']) ? (int)$_POST['point_cost'] : null;
$description = $_POST['description'] ?? null;
$image = $_POST['image'] ?? null;
$stock = isset($_POST['stock']) ? (int)$_POST['stock'] : null;
$is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : null;

// Validasi: Jangan jalankan query jika data penting kosong
if (!$reward_id) {
    echo json_encode([
        "status"  => "error",
        "message" => "Reward ID harus diisi"
    ]);
    exit;
}

// Siapkan query update
$params = [];
$types = "";

$update_fields = [];

if ($reward_name !== null) {
    $update_fields[] = "reward_name = ?";
    $params[] = $reward_name;
    $types .= "s";
}

if ($point_cost !== null) {
    $update_fields[] = "point_cost = ?";
    $params[] = $point_cost;
    $types .= "i";
}

if ($description !== null) {
    $update_fields[] = "description = ?";
    $params[] = $description;
    $types .= "s";
}

if ($image !== null) {
    $update_fields[] = "image = ?";
    $params[] = $image;
    $types .= "s";
}

if ($stock !== null) {
    $update_fields[] = "stock = ?";
    $params[] = $stock;
    $types .= "i";
}

if ($is_active !== null) {
    $update_fields[] = "is_active = ?";
    $params[] = $is_active;
    $types .= "i";
}

// Tambahkan waktu update
$update_fields[] = "updated_at = NOW()";

if (!empty($update_fields)) {
    $sql = "UPDATE rewards SET " . implode(", ", $update_fields) . " WHERE reward_id = ?";
    $params[] = $reward_id;
    $types .= "i";

    try {
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode([
                        "status"  => "success",
                        "message" => "Data reward berhasil diperbarui",
                        "data"    => [
                            "reward_id" => $reward_id,
                            "reward_name" => $reward_name,
                            "point_cost" => $point_cost,
                            "is_active" => $is_active
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