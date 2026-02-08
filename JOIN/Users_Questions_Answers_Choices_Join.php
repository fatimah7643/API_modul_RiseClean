<?php
include '../db.php';

header('Content-Type: application/json');

$data = [];

// Ambil ID bisa dari POST (Body) atau GET (URL) agar fleksibel
$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($id) {
    // Jika ada ID, cari user dengan jawaban kuis mereka beserta detail pertanyaan dan pilihan jawaban berdasarkan user id
    $stmt = $conn->prepare("
        SELECT
            u.id as user_id,
            u.username,
            u.email,
            u.first_name,
            u.last_name,
            u.phone,
            u.avatar,
            u.role_id,
            u.total_xp,
            u.total_points,
            u.current_level,
            u.is_active,
            u.last_login,
            u.created_at,
            u.updated_at,
            uqa.answer_id,
            uqa.module_id,
            uqa.question_id,
            uqa.selected_choice_id,
            uqa.answer_text,
            uqa.is_correct as answer_is_correct,
            uqa.points_earned,
            uqa.xp_earned,
            uqa.created_at as answered_at,
            qq.question_text,
            qq.question_type,
            qq.xp_reward,
            qq.point_reward,
            qq.difficulty,
            qq.is_active,
            qc.choice_text,
            qc.is_correct as choice_is_correct,
            qc.choice_order
        FROM users u
        LEFT JOIN user_quiz_answers uqa ON u.id = uqa.user_id
        LEFT JOIN quiz_questions qq ON uqa.question_id = qq.question_id
        LEFT JOIN quiz_choices qc ON uqa.selected_choice_id = qc.choice_id
        WHERE u.id = ?
    ");
    $stmt->bind_param("i", $id);

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();

} else {
    // Jika tidak ada ID, ambil semua data user dengan jawaban kuis mereka beserta detail pertanyaan dan pilihan jawaban
    $sql = "
        SELECT
            u.id as user_id,
            u.username,
            u.email,
            u.first_name,
            u.last_name,
            u.phone,
            u.avatar,
            u.role_id,
            u.total_xp,
            u.total_points,
            u.current_level,
            u.is_active,
            u.last_login,
            u.created_at,
            u.updated_at,
            uqa.answer_id,
            uqa.module_id,
            uqa.question_id,
            uqa.selected_choice_id,
            uqa.answer_text,
            uqa.is_correct as answer_is_correct,
            uqa.points_earned,
            uqa.xp_earned,
            uqa.created_at as answered_at,
            qq.question_text,
            qq.question_type,
            qq.xp_reward,
            qq.point_reward,
            qq.difficulty,
            qq.is_active,
            qc.choice_text,
            qc.is_correct as choice_is_correct,
            qc.choice_order
        FROM users u
        LEFT JOIN user_quiz_answers uqa ON u.id = uqa.user_id
        LEFT JOIN quiz_questions qq ON uqa.question_id = qq.question_id
        LEFT JOIN quiz_choices qc ON uqa.selected_choice_id = qc.choice_id
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
    "message" => count($data) > 0 ? "Data user, jawaban kuis, pertanyaan, dan pilihan jawaban ditemukan" : "Data kosong",
    "data"    => $data
]);

$conn->close();
?>