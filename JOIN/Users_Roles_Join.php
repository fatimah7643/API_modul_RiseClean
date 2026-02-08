<?php
include '../db.php';

header('Content-Type: application/json');

$data = [];

// Ambil ID bisa dari POST (Body) atau GET (URL) agar fleksibel
$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($id) {
    // Jika ada ID, cari user dengan role-nya berdasarkan user id
    $stmt = $conn->prepare("
        SELECT
            u.id,
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
            r.role_name,
            r.description as role_description
        FROM users u
        LEFT JOIN roles r ON u.role_id = r.id
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
    // Jika tidak ada ID, ambil semua data user dengan role-nya
    $sql = "
        SELECT
            u.id,
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
            r.role_name,
            r.description as role_description
        FROM users u
        LEFT JOIN roles r ON u.role_id = r.id
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
    "message" => count($data) > 0 ? "Data user dan role ditemukan" : "Data kosong",
    "data"    => $data
]);

$conn->close();
?>