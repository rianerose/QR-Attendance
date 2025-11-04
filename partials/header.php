<?php
require_once __DIR__ . '/../bootstrap.php';

$config = require __DIR__ . '/../config.php';

$currentPath = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) ?: 'index.php';

$navItems = [
    'index.php' => 'Dashboard',
    'students.php' => 'Students',
    'add_student.php' => 'Add Student',
    'attendance.php' => 'Scan Attendance',
    'attendance_log.php' => 'Attendance Log',
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Attendance Recorder</title>
    <link rel="stylesheet" href="<?= e($config['app_url']); ?>/assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header class="app-header">
        <div class="container header-container">
            <div class="logo">
                <span class="logo-icon">QR</span>
                <div class="logo-text">
                    <strong>Attendance</strong>
                    <small>Recorder</small>
                </div>
            </div>
            <nav>
                <ul class="nav-list">
                    <?php foreach ($navItems as $file => $label): ?>
                        <li>
                            <a class="<?= $currentPath === $file ? 'active' : ''; ?>" href="<?= e($config['app_url']); ?>/<?= e($file); ?>"><?= e($label); ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <?php if ($message = get_flash('success')): ?>
            <div class="alert success"><?= e($message); ?></div>
        <?php endif; ?>
        <?php if ($message = get_flash('error')): ?>
            <div class="alert error"><?= e($message); ?></div>
        <?php endif; ?>

