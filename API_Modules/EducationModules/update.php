<?php
include '../../db.php';

header('Content-Type: application/json');

// Gunakan $_POST jika kamu pakai x-www-form-urlencoded di Postman
$module_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$title = $_POST['title'] ?? null;
$content = $_POST['content'] ?? null;
$xp_reward = isset($_POST['xp_reward']) ? (int)$_POST['xp_reward'] : null;
$point_reward = isset($_POST['point_reward']) ? (int)$_POST['point_reward'] : null;
$difficulty = $_POST['difficulty'] ?? null;
$category = $_POST['category'] ?? null;
$duration_minutes = isset($_POST['duration_minutes']) ? (int)$_POST['duration_minutes'] : null;
$is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : null;

// Validasi: Jangan jalankan query jika data penting kosong
if (!$module_id) {
    echo json_encode([
        "status"  => "error",
        "message" => "Module ID harus diisi"
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

if ($content !== null) {
    $update_fields[] = "content = ?";
    $params[] = $content;
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

if ($category !== null) {
    $update_fields[] = "category = ?";
    $params[] = $category;
    $types .= "s";
}

if ($duration_minutes !== null) {
    $update_fields[] = "duration_minutes = ?";
    $params[] = $duration_minutes;
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
    $sql = "UPDATE education_modules SET " . implode(", ", $update_fields) . " WHERE module_id = ?";
    $params[] = $module_id;
    $types .= "i";

    try {
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode([
                        "status"  => "success",
                        "message" => "Data education module berhasil diperbarui",
                        "data"    => [
                            "module_id" => $module_id,
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