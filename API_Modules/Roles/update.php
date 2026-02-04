<?php
include '../../db.php';

header('Content-Type: application/json');

// Gunakan $_POST jika kamu pakai x-www-form-urlencoded di Postman
$role_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$role_name = $_POST['role_name'] ?? null;
$description = $_POST['description'] ?? null;

// Validasi: Jangan jalankan query jika data penting kosong
if (!$role_id) {
    echo json_encode([
        "status"  => "error",
        "message" => "Role ID harus diisi"
    ]);
    exit;
}

// Siapkan query update
$params = [];
$types = "";

$update_fields = [];

if ($role_name !== null) {
    $update_fields[] = "role_name = ?";
    $params[] = $role_name;
    $types .= "s";
}

if ($description !== null) {
    $update_fields[] = "description = ?";
    $params[] = $description;
    $types .= "s";
}

// Tambahkan waktu update
$update_fields[] = "created_at = created_at"; // Just to ensure we have at least one field to update

if (!empty($update_fields)) {
    $sql = "UPDATE roles SET " . implode(", ", $update_fields) . " WHERE id = ?";
    $params[] = $role_id;
    $types .= "i";

    try {
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode([
                        "status"  => "success",
                        "message" => "Data role berhasil diperbarui",
                        "data"    => [
                            "id" => $role_id,
                            "role_name" => $role_name,
                            "description" => $description
                        ]
                    ]);
                } else {
                    echo json_encode([
                        "status"  => "error",
                        "message" => "Tidak ada data yang diubah"
                    ]);
                }
            } else {
                throw new Exception($stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception("Prepare failed: " . $conn->error);
        }

    } catch (Exception $e) {
        echo json_encode([
            "status"  => "error",
            "message" => "Gagal update: " . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        "status"  => "error",
        "message" => "Tidak ada data yang diperbarui"
    ]);
}

$conn->close();
?>