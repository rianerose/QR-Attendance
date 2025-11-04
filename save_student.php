<?php
require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: add_student.php');
    exit;
}

$studentIdentifier = trim($_POST['student_identifier'] ?? '');
$fullName = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');

if ($studentIdentifier === '' || $fullName === '' || $email === '') {
    set_flash('error', 'All fields are required.');
    header('Location: add_student.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_flash('error', 'Please provide a valid email address.');
    header('Location: add_student.php');
    exit;
}

$pdo = getPDO();

try {
    $duplicateStmt = $pdo->prepare('SELECT COUNT(*) FROM students WHERE student_identifier = :student_identifier OR email = :email');
    $duplicateStmt->execute([
        ':student_identifier' => $studentIdentifier,
        ':email' => $email,
    ]);

    if ($duplicateStmt->fetchColumn() > 0) {
        set_flash('error', 'A student with that ID or email already exists.');
        header('Location: add_student.php');
        exit;
    }

    $token = strtoupper(bin2hex(random_bytes(8)));

    $insertStmt = $pdo->prepare('INSERT INTO students (student_identifier, full_name, email, qr_token) VALUES (:student_identifier, :full_name, :email, :qr_token)');
    $insertStmt->execute([
        ':student_identifier' => $studentIdentifier,
        ':full_name' => $fullName,
        ':email' => $email,
        ':qr_token' => $token,
    ]);

    set_flash('success', 'Student added successfully. Their QR token has been generated.');
    header('Location: students.php');
    exit;
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    set_flash('error', 'Unable to save student right now. Please try again.');
    header('Location: add_student.php');
    exit;
}

