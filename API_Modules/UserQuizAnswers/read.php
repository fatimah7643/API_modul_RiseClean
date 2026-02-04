<?php
include '../../db.php';

header('Content-Type: application/json');

$data = [];

// Ambil ID bisa dari POST (Body) atau GET (URL) agar fleksibel
$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($id) {
    // Jika ada ID, cari spesifik user quiz answer tersebut
    $stmt = $conn->prepare("SELECT * FROM user_quiz_answers WHERE answer_id = ?");
    $stmt->bind_param("i", $id);

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();

} else {
    // Jika tidak ada ID, ambil semua data
    $sql = "SELECT * FROM user_quiz_answers";
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
    "message" => count($data) > 0 ? "Data ditemukan" : "Data kosong",
    "data"    => $data
]);

$conn->close();
?>