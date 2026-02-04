<?php
include_once '../../db.php';

header('Content-Type: application/json');

// Menangkap input dari form
$user_id = $_POST['user_id'] ?? null;
$module_id = $_POST['module_id'] ?? null;
$question_id = $_POST['question_id'] ?? null;
$selected_choice_id = $_POST['selected_choice_id'] ?? null;
$answer_text = $_POST['answer_text'] ?? null;
$is_correct = $_POST['is_correct'] ?? null;
$points_earned = $_POST['points_earned'] ?? 0;
$xp_earned = $_POST['xp_earned'] ?? 0;

// Validasi input minimal agar tidak terjadi error "cannot be null"
if (!$user_id || !$module_id || !$question_id) {
    echo json_encode([
        "status"  => "error",
        "message" => "User ID, Module ID, dan Question ID tidak boleh kosong"
    ]);
    exit;
}

// Persiapkan Query sesuai skema tabel user_quiz_answers
$stmt = $conn->prepare("
    INSERT INTO user_quiz_answers (user_id, module_id, question_id, selected_choice_id, answer_text, is_correct, points_earned, xp_earned)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

// Bind param: i=integer, i=integer, i=integer, i=integer, s=string, i=integer, i=integer, i=integer
$stmt->bind_param("iiiisisi", $user_id, $module_id, $question_id, $selected_choice_id, $answer_text, $is_correct, $points_earned, $xp_earned);

if ($stmt->execute()) {
    echo json_encode([
        "status"  => "success",
        "message" => "User quiz answer berhasil ditambahkan",
        "data"    => [
            "answer_id" => $stmt->insert_id,
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
        "message" => $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>