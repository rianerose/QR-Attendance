<?php
require_once __DIR__ . '/bootstrap.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
    exit;
}

$payload = json_decode(file_get_contents('php://input'), true);
$token = trim($payload['token'] ?? '');

if ($token === '') {
    echo json_encode([
        'success' => false,
        'message' => 'QR token is required.'
    ]);
    exit;
}

$pdo = getPDO();

try {
    $studentStmt = $pdo->prepare('SELECT id, student_identifier, full_name FROM students WHERE qr_token = :token');
    $studentStmt->execute([':token' => $token]);
    $student = $studentStmt->fetch();

    if (!$student) {
        echo json_encode([
            'success' => false,
            'message' => 'No student found for this QR code.'
        ]);
        exit;
    }

    $recentStmt = $pdo->prepare('SELECT recorded_at FROM attendance WHERE student_id = :student_id ORDER BY recorded_at DESC LIMIT 1');
    $recentStmt->execute([':student_id' => $student['id']]);
    $recent = $recentStmt->fetchColumn();

    if ($recent && (time() - strtotime($recent)) < 60) {
        echo json_encode([
            'success' => false,
            'message' => 'Attendance already captured in the last minute. Please wait before scanning again.'
        ]);
        exit;
    }

    $insertStmt = $pdo->prepare('INSERT INTO attendance (student_id) VALUES (:student_id)');
    $insertStmt->execute([':student_id' => $student['id']]);

    $recordedAt = (new DateTime())->format('Y-m-d H:i:s');

    echo json_encode([
        'success' => true,
        'message' => 'Attendance recorded successfully.',
        'data' => [
            'student_identifier' => $student['student_identifier'],
            'full_name' => $student['full_name'],
            'recorded_at' => $recordedAt,
            'recorded_at_readable' => (new DateTime($recordedAt))->format('M j, Y g:i A')
        ]
    ]);
    exit;
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Database error. Unable to record attendance.'
    ]);
    exit;
}

