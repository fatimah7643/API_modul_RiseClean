<?php
include '../../db.php';

header('Content-Type: application/json');

// Gunakan $_POST jika kamu pakai x-www-form-urlencoded di Postman
$challenge_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$title = $_POST['title'] ?? null;
$description = $_POST['description'] ?? null;
$xp_reward = isset($_POST['xp_reward']) ? (int)$_POST['xp_reward'] : null;
$point_reward = isset($_POST['point_reward']) ? (int)$_POST['point_reward'] : null;
$difficulty = $_POST['difficulty'] ?? null;
$challenge_type = $_POST['challenge_type'] ?? null;
$start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
$end_date   = !empty($_POST['end_date'])   ? $_POST['end_date']   : null;
$is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : null;

// Validasi: Jangan jalankan query jika data penting kosong
if (!$challenge_id) {
    echo json_encode([
        "status"  => "error",
        "message" => "Challenge ID harus diisi"
    ]);
    exit;
}

// Siapkan query update
$params = [];
$types = "";

$update_fields = [];

if ($title !== null) {
    $update_fields[] = "title = ?";
    $params[] = $title;
    $types .= "s";
}

if ($description !== null) {
    $update_fields[] = "description = ?";
    $params[] = $description;
    $types .= "s";
}

if ($xp_reward !== null) {
    $update_fields[] = "xp_reward = ?";
    $params[] = $xp_reward;
    $types .= "i";
}

if ($point_reward !== null) {
    $update_fields[] = "point_reward = ?";
    $params[] = $point_reward;
    $types .= "i";
}

if ($difficulty !== null) {
    $update_fields[] = "difficulty = ?";
    $params[] = $difficulty;
    $types .= "s";
}

if ($challenge_type !== null) {
    $update_fields[] = "challenge_type = ?";
    $params[] = $challenge_type;
    $types .= "s";
}

if ($start_date !== null) {
    $update_fields[] = "start_date = ?";
    $params[] = $start_date;
    $types .= "s";
}

if ($end_date !== null) {
    $update_fields[] = "end_date = ?";
    $params[] = $end_date;
    $types .= "s";
}

if ($is_active !== null) {
    $update_fields[] = "is_active = ?";
    $params[] = $is_active;
    $types .= "i";
}

// Tambahkan waktu update
$update_fields[] = "updated_at = NOW()";

if (!empty($update_fields)) {
    $sql = "UPDATE challenges SET " . implode(", ", $update_fields) . " WHERE challenge_id = ?";
    $params[] = $challenge_id;
    $types .= "i";

    try {
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode([
                        "status"  => "success",
                        "message" => "Data challenge berhasil diperbarui",
                        "data"    => [
                            "challenge_id" => $challenge_id,
                            "title" => $title,
                            "xp_reward" => $xp_reward,
                            "point_reward" => $point_reward,
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