<?php
include '../db.php';

header('Content-Type: application/json');

$data = [];

// Ambil ID bisa dari POST (Body) atau GET (URL) agar fleksibel
$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($id) {
    // Jika ada ID, cari user dengan progress-nya berdasarkan user id
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
            up.progress_id,
            up.item_id,
            up.item_type,
            up.completed_at,
            up.verified_at,
            up.is_verified,
            up.submission_text,
            up.submission_image
        FROM users u
        LEFT JOIN user_progress up ON u.id = up.user_id
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
    // Jika tidak ada ID, ambil semua data user dengan progress-nya
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
            up.progress_id,
            up.item_id,
            up.item_type,
            up.completed_at,
            up.verified_at,
            up.is_verified,
            up.submission_text,
            up.submission_image
        FROM users u
        LEFT JOIN user_progress up ON u.id = up.user_id
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
    "message" => count($data) > 0 ? "Data user dan progress ditemukan" : "Data kosong",
    "data"    => $data
]);

$conn->close();
?>