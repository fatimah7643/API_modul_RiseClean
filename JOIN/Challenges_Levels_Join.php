<?php
include '../db.php';

header('Content-Type: application/json');

$data = [];

// Ambil ID bisa dari POST (Body) atau GET (URL) agar fleksibel
$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($id) {
    // Jika ada ID, cari challenge dengan level-nya berdasarkan challenge id
    // Berdasarkan struktur database, tidak ada hubungan langsung antara challenges dan levels
    // Jadi kita hanya menampilkan data challenge saja
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
            c.updated_at as challenge_updated_at
        FROM challenges c
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
    // Jika tidak ada ID, ambil semua data challenge
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
            c.updated_at as challenge_updated_at
        FROM challenges c
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
    "message" => count($data) > 0 ? "Data challenge ditemukan" : "Data kosong",
    "data"    => $data
]);

$conn->close();
?>