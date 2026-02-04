<?php
include_once '../../db.php';

header('Content-Type: application/json');

// Menangkap input dari form
$role_name = $_POST['role_name'] ?? null;
$description = $_POST['description'] ?? null;

// Validasi input minimal agar tidak terjadi error "cannot be null"
if (empty($role_name)) {
    echo json_encode([
        "status"  => "error",
        "message" => "Role name tidak boleh kosong"
    ]);
    exit;
}

// Cek apakah role_name sudah ada
$check_role = $conn->prepare("SELECT id FROM roles WHERE role_name = ?");
$check_role->bind_param("s", $role_name);
$check_role->execute();
$result = $check_role->get_result();

if ($result->num_rows > 0) {
    echo json_encode([
        "status"  => "error",
        "message" => "Role name sudah terdaftar"
    ]);
    exit;
}

// Persiapkan Query sesuai skema tabel roles
$stmt = $conn->prepare("
    INSERT INTO roles (role_name, description)
    VALUES (?, ?)
");

// Bind param: s=string, s=string
$stmt->bind_param("ss", $role_name, $description);

if ($stmt->execute()) {
    echo json_encode([
        "status"  => "success",
        "message" => "Role berhasil ditambahkan",
        "data"    => [
            "id" => $stmt->insert_id,
            "role_name" => $role_name
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