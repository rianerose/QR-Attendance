<?php
require_once __DIR__ . '/partials/header.php';

$pdo = getPDO();

try {
    $studentCount = (int) $pdo->query('SELECT COUNT(*) AS total FROM students')->fetchColumn();

    $attendanceTotal = (int) $pdo->query('SELECT COUNT(*) AS total FROM attendance')->fetchColumn();

    $attendanceTodayStmt = $pdo->prepare('SELECT COUNT(*) FROM attendance WHERE DATE(recorded_at) = CURDATE()');
    $attendanceTodayStmt->execute();
    $attendanceToday = (int) $attendanceTodayStmt->fetchColumn();

    $recentAttendance = $pdo->query('SELECT attendance.id, attendance.recorded_at, students.full_name, students.student_identifier FROM attendance JOIN students ON students.id = attendance.student_id ORDER BY attendance.recorded_at DESC LIMIT 5')->fetchAll();
} catch (PDOException $exception) {
    $studentCount = 0;
    $attendanceTotal = 0;
    $attendanceToday = 0;
    $recentAttendance = [];
}

?>

<section class="section">
    <div class="section-header">
        <h2>Overview</h2>
        <a class="btn" href="add_student.php">Add Student</a>
    </div>

    <div class="grid two-columns">
        <div class="stat-card">
            <h3>Total Students</h3>
            <p class="stat-number"><?= $studentCount; ?></p>
        </div>
        <div class="stat-card">
            <h3>Attendance Today</h3>
            <p class="stat-number"><?= $attendanceToday; ?></p>
        </div>
        <div class="stat-card">
            <h3>Total Attendance Records</h3>
            <p class="stat-number"><?= $attendanceTotal; ?></p>
        </div>
        <div class="stat-card">
            <h3>Quick Actions</h3>
            <div class="grid">
                <a class="btn btn-secondary" href="attendance.php">Open Scanner</a>
                <a class="btn" href="attendance_log.php">View Attendance Log</a>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="section-header">
        <h2>Recent Attendance</h2>
    </div>

    <?php if (empty($recentAttendance)): ?>
        <div class="empty-state">No attendance captured yet.</div>
    <?php else: ?>
        <div class="attendance-list">
            <?php foreach ($recentAttendance as $record): ?>
                <div class="attendance-item">
                    <div>
                        <strong><?= e($record['full_name']); ?></strong>
                        <div class="badge">ID: <?= e($record['student_identifier']); ?></div>
                    </div>
                    <span data-attendance-time="<?= e($record['recorded_at']); ?>"></span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

