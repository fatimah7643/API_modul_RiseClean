<?php
include '../../db.php';

header('Content-Type: application/json');

// Gunakan $_POST jika kamu pakai x-www-form-urlencoded di Postman
$choice_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$question_id = isset($_POST['question_id']) ? (int)$_POST['question_id'] : null;
$choice_text = $_POST['choice_text'] ?? null;
$is_correct = isset($_POST['is_correct']) ? (int)$_POST['is_correct'] : null;
$choice_order = isset($_POST['choice_order']) ? (int)$_POST['choice_order'] : null;

// Validasi: Jangan jalankan query jika data penting kosong
if (!$choice_id) {
    echo json_encode([
        "status"  => "error",
        "message" => "Choice ID harus diisi"
    ]);
    exit;
}

// Siapkan query update
$params = [];
$types = "";

$update_fields = [];

if ($question_id !== null) {
    $update_fields[] = "question_id = ?";
    $params[] = $question_id;
    $types .= "i";
}

if ($choice_text !== null) {
    $update_fields[] = "choice_text = ?";
    $params[] = $choice_text;
    $types .= "s";
}

if ($is_correct !== null) {
    $update_fields[] = "is_correct = ?";
    $params[] = $is_correct;
    $types .= "i";
}

if ($choice_order !== null) {
    $update_fields[] = "choice_order = ?";
    $params[] = $choice_order;
    $types .= "i";
}

if (!empty($update_fields)) {
    $sql = "UPDATE quiz_choices SET " . implode(", ", $update_fields) . " WHERE choice_id = ?";
    $params[] = $choice_id;
    $types .= "i";

    try {
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode([
                        "status"  => "success",
                        "message" => "Data quiz choice berhasil diperbarui",
                        "data"    => [
                            "choice_id" => $choice_id,
                            "question_id" => $question_id,
                            "choice_text" => $choice_text,
                            "is_correct" => $is_correct
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