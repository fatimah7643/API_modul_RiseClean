<?php
include '../../db.php';

header('Content-Type: application/json');

// Gunakan $_POST jika kamu pakai x-www-form-urlencoded di Postman
$progress_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;
$item_id = isset($_POST['item_id']) ? (int)$_POST['item_id'] : null;
$item_type = $_POST['item_type'] ?? null;
$verified_at = $_POST['verified_at'] ?? null;
$is_verified = isset($_POST['is_verified']) ? (int)$_POST['is_verified'] : null;
$submission_text = $_POST['submission_text'] ?? null;
$submission_image = $_POST['submission_image'] ?? null;

// Validasi: Jangan jalankan query jika data penting kosong
if (!$progress_id) {
    echo json_encode([
        "status"  => "error",
        "message" => "Progress ID harus diisi"
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

if ($item_id !== null) {
    $update_fields[] = "item_id = ?";
    $params[] = $item_id;
    $types .= "i";
}

if ($item_type !== null) {
    $update_fields[] = "item_type = ?";
    $params[] = $item_type;
    $types .= "s";
}

if ($verified_at !== null) {
    $update_fields[] = "verified_at = ?";
    $params[] = $verified_at;
    $types .= "s";
}

if ($is_verified !== null) {
    $update_fields[] = "is_verified = ?";
    $params[] = $is_verified;
    $types .= "i";
}

if ($submission_text !== null) {
    $update_fields[] = "submission_text = ?";
    $params[] = $submission_text;
    $types .= "s";
}

if ($submission_image !== null) {
    $update_fields[] = "submission_image = ?";
    $params[] = $submission_image;
    $types .= "s";
}

if (!empty($update_fields)) {
    $sql = "UPDATE user_progress SET " . implode(", ", $update_fields) . " WHERE progress_id = ?";
    $params[] = $progress_id;
    $types .= "i";

    try {
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode([
                        "status"  => "success",
                        "message" => "Data user progress berhasil diperbarui",
                        "data"    => [
                            "progress_id" => $progress_id,
                            "user_id" => $user_id,
                            "item_id" => $item_id,
                            "item_type" => $item_type,
                            "is_verified" => $is_verified
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