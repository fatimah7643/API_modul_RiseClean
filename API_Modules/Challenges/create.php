<?php
include_once '../../db.php';

header('Content-Type: application/json');

// Menangkap input dari form
$title = $_POST['title'] ?? null;
$description = $_POST['description'] ?? null;
$xp_reward = $_POST['xp_reward'] ?? 20;
$point_reward = $_POST['point_reward'] ?? 10;
$difficulty = $_POST['difficulty'] ?? 'medium'; // easy, medium, hard
$challenge_type = $_POST['challenge_type'] ?? 'daily'; // daily, special, weekly
$start_date = $_POST['start_date'] ?? date('Y-m-d');
$end_date = $_POST['end_date'] ?? null;
$is_active = $_POST['is_active'] ?? 1;

// Validasi input minimal agar tidak terjadi error "cannot be null"
if (empty($title)) {
    echo json_encode([
        "status"  => "error",
        "message" => "Title tidak boleh kosong"
    ]);
    exit;
}

// Persiapkan Query sesuai skema tabel challenges
$stmt = $conn->prepare("
    INSERT INTO challenges (title, description, xp_reward, point_reward, difficulty, challenge_type, start_date, end_date, is_active)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");

// Bind param: s=string, s=string, i=integer, i=integer, s=string, s=string, s=string, s=string, i=integer
$stmt->bind_param("ssiissssi", $title, $description, $xp_reward, $point_reward, $difficulty, $challenge_type, $start_date, $end_date, $is_active);

if ($stmt->execute()) {
    echo json_encode([
        "status"  => "success",
        "message" => "Challenge berhasil ditambahkan",
        "data"    => [
            "challenge_id" => $stmt->insert_id,
            "title" => $title,
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