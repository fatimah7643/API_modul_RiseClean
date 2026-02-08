<?php
include_once '../../db.php';

header('Content-Type: application/json');

// Menangkap input dari form
$question_id = $_POST['question_id'] ?? null;
$choice_text = $_POST['choice_text'] ?? null;
$is_correct = $_POST['is_correct'] ?? null;
$choice_order = $_POST['choice_order'] ?? null;

// Validasi input minimal agar tidak terjadi error "cannot be null"
if (empty($choice_text)) {
    echo json_encode([
        "status"  => "error",
        "message" => "Choice text tidak boleh kosong"
    ]);
    exit;
}

// Persiapkan Query sesuai skema tabel quiz_choices
$stmt = $conn->prepare("
    INSERT INTO quiz_choices (question_id, choice_text, is_correct, choice_order)
    VALUES (?, ?, ?, ?)
");

// Bind param: i=integer, s=string, i=integer, i=integer
$stmt->bind_param("isii", $question_id, $choice_text, $is_correct, $choice_order);

if ($stmt->execute()) {
    echo json_encode([
        "status"  => "success",
        "message" => "Quiz choice berhasil ditambahkan",
        "data"    => [
            "choice_id" => $stmt->insert_id,
            "question_id" => $question_id,
            "choice_text" => $choice_text,
            "is_correct" => $is_correct
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