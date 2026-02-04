<?php
include_once '../../db.php';

header('Content-Type: application/json');

// Menangkap input dari form
$username = $_POST['username'] ?? null;
$ip_address = $_POST['ip_address'] ?? $_SERVER['REMOTE_ADDR'];
$attempts = $_POST['attempts'] ?? 1;
$last_attempt = $_POST['last_attempt'] ?? date('Y-m-d H:i:s');
$blocked_until = $_POST['blocked_until'] ?? null;

// Validasi input minimal agar tidak terjadi error "cannot be null"
if (empty($username)) {
    echo json_encode([
        "status"  => "error",
        "message" => "Username tidak boleh kosong"
    ]);
    exit;
}

// Persiapkan Query sesuai skema tabel failed_login_attempts
$stmt = $conn->prepare("
    INSERT INTO failed_login_attempts (username, ip_address, attempts, last_attempt, blocked_until)
    VALUES (?, ?, ?, ?, ?)
");

// Bind param: s=string, s=string, i=integer, s=string, s=string
$stmt->bind_param("sssds", $username, $ip_address, $attempts, $last_attempt, $blocked_until);

if ($stmt->execute()) {
    echo json_encode([
        "status"  => "success",
        "message" => "Failed login attempt berhasil ditambahkan",
        "data"    => [
            "id" => $stmt->insert_id,
            "username" => $username,
            "ip_address" => $ip_address
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