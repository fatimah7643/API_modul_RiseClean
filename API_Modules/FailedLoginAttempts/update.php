<?php
include '../../db.php';

header('Content-Type: application/json');

// Gunakan $_POST jika kamu pakai x-www-form-urlencoded di Postman
$attempt_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$username = $_POST['username'] ?? null;
$ip_address = $_POST['ip_address'] ?? null;
$attempts = isset($_POST['attempts']) ? (int)$_POST['attempts'] : null;
$last_attempt = $_POST['last_attempt'] ?? null;
$blocked_until = $_POST['blocked_until'] ?? null;

// Validasi: Jangan jalankan query jika data penting kosong
if (!$attempt_id) {
    echo json_encode([
        "status"  => "error",
        "message" => "Attempt ID harus diisi"
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

if ($ip_address !== null) {
    $update_fields[] = "ip_address = ?";
    $params[] = $ip_address;
    $types .= "s";
}

if ($attempts !== null) {
    $update_fields[] = "attempts = ?";
    $params[] = $attempts;
    $types .= "i";
}

if ($last_attempt !== null) {
    $update_fields[] = "last_attempt = ?";
    $params[] = $last_attempt;
    $types .= "s";
}

if ($blocked_until !== null) {
    $update_fields[] = "blocked_until = ?";
    $params[] = $blocked_until;
    $types .= "s";
}

if (!empty($update_fields)) {
    $sql = "UPDATE failed_login_attempts SET " . implode(", ", $update_fields) . " WHERE id = ?";
    $params[] = $attempt_id;
    $types .= "i";

    try {
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    echo json_encode([
                        "status"  => "success",
                        "message" => "Data failed login attempt berhasil diperbarui",
                        "data"    => [
                            "id" => $attempt_id,
                            "username" => $username,
                            "ip_address" => $ip_address
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