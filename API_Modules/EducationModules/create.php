<?php
include_once '../../db.php';

header('Content-Type: application/json');

// Menangkap input dari form
$title = $_POST['title'] ?? null;
$content = $_POST['content'] ?? null;
$xp_reward = $_POST['xp_reward'] ?? 10;
$point_reward = $_POST['point_reward'] ?? 5;
$difficulty = $_POST['difficulty'] ?? 'medium'; // easy, medium, hard
$category = $_POST['category'] ?? null;
$duration_minutes = $_POST['duration_minutes'] ?? 10;
$is_active = $_POST['is_active'] ?? 1;

// Validasi input minimal agar tidak terjadi error "cannot be null"
if (empty($title)) {
    echo json_encode([
        "status"  => "error",
        "message" => "Title tidak boleh kosong"
    ]);
    exit;
}

// Persiapkan Query sesuai skema tabel education_modules
$stmt = $conn->prepare("
    INSERT INTO education_modules (title, content, xp_reward, point_reward, difficulty, category, duration_minutes, is_active)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

// Bind param: s=string, i=integer, i=integer, s=string, s=string, i=integer, i=integer
$stmt->bind_param("ssiisiii", $title, $content, $xp_reward, $point_reward, $difficulty, $category, $duration_minutes, $is_active);

if ($stmt->execute()) {
    echo json_encode([
        "status"  => "success",
        "message" => "Education module berhasil ditambahkan",
        "data"    => [
            "module_id" => $stmt->insert_id,
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