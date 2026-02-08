<?php
include '../db.php';

header('Content-Type: application/json');

$data = [];

// Ambil ID bisa dari POST (Body) atau GET (URL) agar fleksibel
$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($id) {
    // Jika ada ID, cari pertanyaan kuis dengan pilihan jawabannya berdasarkan question id
    $stmt = $conn->prepare("
        SELECT
            qq.question_id,
            qq.module_id,
            qq.question_text,
            qq.question_type,
            qq.xp_reward,
            qq.point_reward,
            qq.difficulty,
            qq.is_active,
            qc.choice_id,
            qc.choice_text,
            qc.is_correct,
            qc.choice_order
        FROM quiz_questions qq
        LEFT JOIN quiz_choices qc ON qq.question_id = qc.question_id
        WHERE qq.question_id = ?
    ");
    $stmt->bind_param("i", $id);

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();

} else {
    // Jika tidak ada ID, ambil semua data pertanyaan kuis dengan pilihan jawabannya
    $sql = "
        SELECT
            qq.question_id,
            qq.module_id,
            qq.question_text,
            qq.question_type,
            qq.xp_reward,
            qq.point_reward,
            qq.difficulty,
            qq.is_active,
            qc.choice_id,
            qc.choice_text,
            qc.is_correct,
            qc.choice_order
        FROM quiz_questions qq
        LEFT JOIN quiz_choices qc ON qq.question_id = qc.question_id
    ";
    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
}

// Output JSON
echo json_encode([
    "status"  => "success",
    "message" => count($data) > 0 ? "Data pertanyaan kuis dan pilihan jawaban ditemukan" : "Data kosong",
    "data"    => $data
]);

$conn->close();
?>