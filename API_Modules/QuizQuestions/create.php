<?php
include_once '../../db.php';

header('Content-Type: application/json');

// Menangkap input dari form
$module_id = $_POST['module_id'] ?? null;
$question_text = $_POST['question_text'] ?? null;
$question_type = $_POST['question_type'] ?? 'multiple_choice'; // multiple_choice, true_false
$xp_reward = $_POST['xp_reward'] ?? 10;
$point_reward = $_POST['point_reward'] ?? 5;
$difficulty = $_POST['difficulty'] ?? 'easy'; // easy, medium, hard
$is_active = $_POST['is_active'] ?? 1;

// Validasi input minimal agar tidak terjadi error "cannot be null"
if (empty($question_text)) {
    echo json_encode([
        "status"  => "error",
        "message" => "Question text tidak boleh kosong"
    ]);
    exit;
}

// Persiapkan Query sesuai skema tabel quiz_questions
$stmt = $conn->prepare("
    INSERT INTO quiz_questions (module_id, question_text, question_type, xp_reward, point_reward, difficulty, is_active)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

// Bind param: i=integer, s=string, s=string, i=integer, i=integer, s=string, i=integer
$stmt->bind_param("issiisi", $module_id, $question_text, $question_type, $xp_reward, $point_reward, $difficulty, $is_active);

if ($stmt->execute()) {
    echo json_encode([
        "status"  => "success",
        "message" => "Quiz question berhasil ditambahkan",
        "data"    => [
            "question_id" => $stmt->insert_id,
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
        "message" => $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>