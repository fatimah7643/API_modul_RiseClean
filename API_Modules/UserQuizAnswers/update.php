<?php
include '../../db.php';

header('Content-Type: application/json');

// Gunakan $_POST jika kamu pakai x-www-form-urlencoded di Postman
$answer_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : null;
$module_id = isset($_POST['module_id']) ? (int)$_POST['module_id'] : null;
$question_id = isset($_POST['question_id']) ? (int)$_POST['question_id'] : null;
$selected_choice_id = isset($_POST['selected_choice_id']) ? (int)$_POST['selected_choice_id'] : null;
$answer_text = $_POST['answer_text'] ?? null;
$is_correct = isset($_POST['is_correct']) ? (int)$_POST['is_correct'] : null;
$points_earned = isset($_POST['points_earned']) ? (int)$_POST['points_earned'] : null;
$xp_earned = isset($_POST['xp_earned']) ? (int)$_POST['xp_earned'] : null;

// Validasi: Jangan jalankan query jika data penting kosong
if (!$answer_id) {
    echo json_encode([
        "status"  => "error",
        "message" => "Answer ID harus diisi"
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

if ($module_id !== null) {
    $update_fields[] = "module_id = ?";
    $params[] = $module_id;
    $types .= "i";
}

if ($question_id !== null) {
    $update_fields[] = "question_id = ?";
    $params[] = $question_id;
    $types .= "i";
}

if ($selected_choice_id !== null) {
    $update_fields[] = "selected_choice_id = ?";
    $params[] = $selected_choice_id;
    $types .= "i";
}

if ($answer_text !== null) {
    $update_fields[] = "answer_text = ?";
    $params[] = $answer_text;
    $types .= "s";
}

if ($is_correct !== null) {
    $update_fields[] = "is_correct = ?";
    $params[] = $is_correct;
    $types .= "i";
}

if ($points_earned !== null) {
    $update_fields[] = "points_earned = ?";
    $params[] = $points_earned;
    $types .= "i";
}

if ($xp_earned !== null) {
    $update_fields[] = "xp_earned = ?";
    $params[] = $xp_earned;
    $types .= "i";
}

if (!empty($update_fields)) {
    $sql = "UPDATE user_quiz_answers SET " . implode(", ", $update_fields) . " WHERE answer_id = ?";
    $params[] = $answer_id;
    $types .= "i";

    try {
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode([
                        "status"  => "success",
                        "message" => "Data user quiz answer berhasil diperbarui",
                        "data"    => [
                            "answer_id" => $answer_id,
                            "user_id" => $user_id,
                            "module_id" => $module_id,
                            "question_id" => $question_id,
                            "points_earned" => $points_earned,
                            "xp_earned" => $xp_earned
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