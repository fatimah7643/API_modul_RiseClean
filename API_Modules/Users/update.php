<?php
include '../../db.php';

header('Content-Type: application/json');

// Gunakan $_POST jika kamu pakai x-www-form-urlencoded di Postman
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$username = $_POST['username'] ?? null;
$email = $_POST['email'] ?? null;
$first_name = $_POST['first_name'] ?? null;
$last_name = $_POST['last_name'] ?? null;
$phone = $_POST['phone'] ?? null;
$role_id = isset($_POST['role_id']) ? (int)$_POST['role_id'] : null;
$total_xp = isset($_POST['total_xp']) ? (int)$_POST['total_xp'] : null;
$total_points = isset($_POST['total_points']) ? (int)$_POST['total_points'] : null;
$current_level = isset($_POST['current_level']) ? (int)$_POST['current_level'] : null;
$is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : null;

// Validasi: Jangan jalankan query jika data penting kosong
if (!$id) {
    echo json_encode([
        "status"  => "error",
        "message" => "ID User harus diisi"
    ]);
    exit;
}

// Siapkan query update
$params = [];
$types = "";

$update_fields = [];

if ($username !== null) {
    $update_fields[] = "username = ?";
    $params[] = $username;
    $types .= "s";
}

if ($email !== null) {
    $update_fields[] = "email = ?";
    $params[] = $email;
    $types .= "s";
}

if ($first_name !== null) {
    $update_fields[] = "first_name = ?";
    $params[] = $first_name;
    $types .= "s";
}

if ($last_name !== null) {
    $update_fields[] = "last_name = ?";
    $params[] = $last_name;
    $types .= "s";
}

if ($phone !== null) {
    $update_fields[] = "phone = ?";
    $params[] = $phone;
    $types .= "s";
}

if ($role_id !== null) {
    $update_fields[] = "role_id = ?";
    $params[] = $role_id;
    $types .= "i";
}

if ($total_xp !== null) {
    $update_fields[] = "total_xp = ?";
    $params[] = $total_xp;
    $types .= "i";
}

if ($total_points !== null) {
    $update_fields[] = "total_points = ?";
    $params[] = $total_points;
    $types .= "i";
}

if ($current_level !== null) {
    $update_fields[] = "current_level = ?";
    $params[] = $current_level;
    $types .= "i";
}

if ($is_active !== null) {
    $update_fields[] = "is_active = ?";
    $params[] = $is_active;
    $types .= "i";
}

// Tambahkan waktu update
$update_fields[] = "updated_at = NOW()";

if (!empty($update_fields)) {
    $sql = "UPDATE users SET " . implode(", ", $update_fields) . " WHERE id = ?";
    $params[] = $id;
    $types .= "i";

    try {
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode([
                        "status"  => "success",
                        "message" => "Data berhasil diperbarui",
                        "data"    => [
                            "id"            => $id,
                            "username"      => $username,
                            "email"         => $email,
                            "first_name"    => $first_name,
                            "last_name"     => $last_name,
                            "phone"         => $phone,
                            "role_id"       => $role_id,
                            "total_xp"      => $total_xp,
                            "total_points"  => $total_points,
                            "current_level" => $current_level,
                            "is_active"     => $is_active
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