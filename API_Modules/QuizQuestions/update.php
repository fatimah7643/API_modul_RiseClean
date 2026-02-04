<?php
include '../../db.php';

header('Content-Type: application/json');

// Gunakan $_POST jika kamu pakai x-www-form-urlencoded di Postman
$question_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$module_id = isset($_POST['module_id']) ? (int)$_POST['module_id'] : null;
$question_text = $_POST['question_text'] ?? null;
$question_type = $_POST['question_type'] ?? null;
$xp_reward = isset($_POST['xp_reward']) ? (int)$_POST['xp_reward'] : null;
$point_reward = isset($_POST['point_reward']) ? (int)$_POST['point_reward'] : null;
$difficulty = $_POST['difficulty'] ?? null;
$is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : null;

// Validasi: Jangan jalankan query jika data penting kosong
if (!$question_id) {
    echo json_encode([
        "status"  => "error",
        "message" => "Question ID harus diisi"
    ]);
    exit;
}

// Siapkan query update
$params = [];
$types = "";

$update_fields = [];

if ($module_id !== null) {
    $update_fields[] = "module_id = ?";
    $params[] = $module_id;
    $types .= "i";
}

if ($question_text !== null) {
    $update_fields[] = "question_text = ?";
    $params[] = $question_text;
    $types .= "s";
}

if ($question_type !== null) {
    $update_fields[] = "question_type = ?";
    $params[] = $question_type;
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

if ($is_active !== null) {
    $update_fields[] = "is_active = ?";
    $params[] = $is_active;
    $types .= "i";
}

// Tambahkan waktu update
$update_fields[] = "updated_at = NOW()";

if (!empty($update_fields)) {
    $sql = "UPDATE quiz_questions SET " . implode(", ", $update_fields) . " WHERE question_id = ?";
    $params[] = $question_id;
    $types .= "i";

    try {
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode([
                        "status"  => "success",
                        "message" => "Data quiz question berhasil diperbarui",
                        "data"    => [
                            "question_id" => $question_id,
                            "module_id" => $module_id,
                            "question_text" => $question_text,
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