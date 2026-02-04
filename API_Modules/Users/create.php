<?php
include_once '../../db.php';

header('Content-Type: application/json');

// Menangkap input dari form
$username = $_POST['username'] ?? null;
$email = $_POST['email'] ?? null;
$password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
$first_name = $_POST['first_name'] ?? null;
$last_name = $_POST['last_name'] ?? null;
$phone = $_POST['phone'] ?? null;
$role_id = $_POST['role_id'] ?? 4; // Default role_id adalah 4 (user)
$total_xp = $_POST['total_xp'] ?? 0;
$total_points = $_POST['total_points'] ?? 0;
$current_level = $_POST['current_level'] ?? 1;
$is_active = $_POST['is_active'] ?? 1;

// Validasi input minimal agar tidak terjadi error "cannot be null"
if (empty($username) || empty($email)) {
    echo json_encode([
        "status"  => "error",
        "message" => "Username dan Email tidak boleh kosong"
    ]);
    exit;
}

// Cek apakah email sudah terdaftar
$check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check_email->bind_param("s", $email);
$check_email->execute();
$result = $check_email->get_result();

if ($result->num_rows > 0) {
    echo json_encode([
        "status"  => "error",
        "message" => "Email sudah terdaftar"
    ]);
    exit;
}

// Cek apakah username sudah terdaftar
$check_username = $conn->prepare("SELECT id FROM users WHERE username = ?");
$check_username->bind_param("s", $username);
$check_username->execute();
$result = $check_username->get_result();

if ($result->num_rows > 0) {
    echo json_encode([
        "status"  => "error",
        "message" => "Username sudah terdaftar"
    ]);
    exit;
}

// Persiapkan Query sesuai skema tabel users
$stmt = $conn->prepare("
    INSERT INTO users (username, email, password, first_name, last_name, phone, role_id, total_xp, total_points, current_level, is_active)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

// Bind param: s=string, i=integer (urutan sesuai VALUES)
$stmt->bind_param("ssssssiisii", $username, $email, $password, $first_name, $last_name, $phone, $role_id, $total_xp, $total_points, $current_level, $is_active);

if ($stmt->execute()) {
    echo json_encode([
        "status"  => "success",
        "message" => "User berhasil didaftarkan",
        "data"    => [
            "id"            => $stmt->insert_id,
            "username"      => $username,
            "email"         => $email,
            "first_name"    => $first_name,
            "last_name"     => $last_name,
            "total_xp"      => $total_xp,
            "total_points"  => $total_points,
            "current_level" => $current_level
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