<?php
include '../../db.php';

header('Content-Type: application/json');

// Gunakan $_POST jika kamu pakai x-www-form-urlencoded di Postman
$level_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$level_name = $_POST['level_name'] ?? null;
$min_xp = isset($_POST['min_xp']) ? (int)$_POST['min_xp'] : null;

// Validasi: Jangan jalankan query jika data penting kosong
if (!$level_id) {
    echo json_encode([
        "status"  => "error",
        "message" => "Level ID harus diisi"
    ]);
    exit;
}

// Siapkan query update
$params = [];
$types = "";

$update_fields = [];

if ($level_name !== null) {
    $update_fields[] = "level_name = ?";
    $params[] = $level_name;
    $types .= "s";
}

if ($min_xp !== null) {
    $update_fields[] = "min_xp = ?";
    $params[] = $min_xp;
    $types .= "i";
}

if (!empty($update_fields)) {
    $sql = "UPDATE levels SET " . implode(", ", $update_fields) . " WHERE level_id = ?";
    $params[] = $level_id;
    $types .= "i";

    try {
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode([
                        "status"  => "success",
                        "message" => "Data level berhasil diperbarui",
                        "data"    => [
                            "level_id" => $level_id,
                            "level_name" => $level_name,
                            "min_xp" => $min_xp
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