<?php
include '../db.php';

header('Content-Type: application/json');

$data = [];

// Ambil ID bisa dari POST (Body) atau GET (URL) agar fleksibel
$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($id) {
    // Jika ada ID, cari education module dengan progress user-nya berdasarkan module id
    $stmt = $conn->prepare("
        SELECT 
            em.module_id,
            em.title,
            em.content,
            em.xp_reward,
            em.point_reward,
            em.difficulty,
            em.category,
            em.duration_minutes,
            em.is_active,
            em.created_at as module_created_at,
            em.updated_at as module_updated_at,
            up.progress_id,
            up.user_id,
            up.item_id,
            up.item_type,
            up.completed_at,
            up.verified_at,
            up.is_verified,
            up.submission_text,
            up.submission_image
        FROM education_modules em
        LEFT JOIN user_progress up ON em.module_id = up.item_id AND up.item_type = 'module'
        WHERE em.module_id = ?
    ");
    $stmt->bind_param("i", $id);

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();

} else {
    // Jika tidak ada ID, ambil semua data education module dengan progress user-nya
    $sql = "
        SELECT 
            em.module_id,
            em.title,
            em.content,
            em.xp_reward,
            em.point_reward,
            em.difficulty,
            em.category,
            em.duration_minutes,
            em.is_active,
            em.created_at as module_created_at,
            em.updated_at as module_updated_at,
            up.progress_id,
            up.user_id,
            up.item_id,
            up.item_type,
            up.completed_at,
            up.verified_at,
            up.is_verified,
            up.submission_text,
            up.submission_image
        FROM education_modules em
        LEFT JOIN user_progress up ON em.module_id = up.item_id AND up.item_type = 'module'
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
    "message" => count($data) > 0 ? "Data education module dan progress user ditemukan" : "Data kosong",
    "data"    => $data
]);

$conn->close();
?>