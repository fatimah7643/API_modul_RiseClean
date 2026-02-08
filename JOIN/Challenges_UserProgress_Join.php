<?php
include '../db.php';

header('Content-Type: application/json');

$data = [];

// Ambil ID bisa dari POST (Body) atau GET (URL) agar fleksibel
$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($id) {
    // Jika ada ID, cari challenge dengan progress user-nya berdasarkan challenge id
    $stmt = $conn->prepare("
        SELECT 
            c.challenge_id,
            c.title,
            c.description,
            c.xp_reward,
            c.point_reward,
            c.difficulty,
            c.challenge_type,
            c.start_date,
            c.end_date,
            c.is_active,
            c.created_at as challenge_created_at,
            c.updated_at as challenge_updated_at,
            up.progress_id,
            up.user_id,
            up.item_id,
            up.item_type,
            up.completed_at,
            up.verified_at,
            up.is_verified,
            up.submission_text,
            up.submission_image
        FROM challenges c
        LEFT JOIN user_progress up ON c.challenge_id = up.item_id AND up.item_type = 'challenge'
        WHERE c.challenge_id = ?
    ");
    $stmt->bind_param("i", $id);

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();

} else {
    // Jika tidak ada ID, ambil semua data challenge dengan progress user-nya
    $sql = "
        SELECT 
            c.challenge_id,
            c.title,
            c.description,
            c.xp_reward,
            c.point_reward,
            c.difficulty,
            c.challenge_type,
            c.start_date,
            c.end_date,
            c.is_active,
            c.created_at as challenge_created_at,
            c.updated_at as challenge_updated_at,
            up.progress_id,
            up.user_id,
            up.item_id,
            up.item_type,
            up.completed_at,
            up.verified_at,
            up.is_verified,
            up.submission_text,
            up.submission_image
        FROM challenges c
        LEFT JOIN user_progress up ON c.challenge_id = up.item_id AND up.item_type = 'challenge'
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
    "message" => count($data) > 0 ? "Data challenge dan progress user ditemukan" : "Data kosong",
    "data"    => $data
]);

$conn->close();
?>