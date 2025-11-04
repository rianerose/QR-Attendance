<?php
require_once __DIR__ . '/partials/header.php';

$pdo = getPDO();

$from = trim($_GET['from'] ?? '');
$to = trim($_GET['to'] ?? '');
$search = trim($_GET['search'] ?? '');

$query = 'SELECT attendance.id, attendance.recorded_at, students.full_name, students.student_identifier, students.email FROM attendance JOIN students ON students.id = attendance.student_id';
$conditions = [];
$params = [];

if ($from !== '') {
    $conditions[] = 'attendance.recorded_at >= :from';
    $params[':from'] = $from . ' 00:00:00';
}

if ($to !== '') {
    $conditions[] = 'attendance.recorded_at <= :to';
    $params[':to'] = $to . ' 23:59:59';
}

if ($search !== '') {
    $conditions[] = '(students.full_name LIKE :search OR students.student_identifier LIKE :search)';
    $params[':search'] = '%' . $search . '%';
}

if ($conditions) {
    $query .= ' WHERE ' . implode(' AND ', $conditions);
}

$query .= ' ORDER BY attendance.recorded_at DESC LIMIT 150';

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $records = $stmt->fetchAll();
} catch (PDOException $exception) {
    $records = [];
}

?>

<section class="section">
    <div class="section-header">
        <h2>Attendance Log</h2>
    </div>

    <form class="grid two-columns" method="get" action="attendance_log.php">
        <div class="form-group">
            <label for="from">From date</label>
            <input type="date" id="from" name="from" value="<?= e($from); ?>">
        </div>
        <div class="form-group">
            <label for="to">To date</label>
            <input type="date" id="to" name="to" value="<?= e($to); ?>">
        </div>
        <div class="form-group">
            <label for="search">Student name or ID</label>
            <input type="text" id="search" name="search" value="<?= e($search); ?>" placeholder="Search...">
        </div>
        <div class="form-group" style="align-self: end;">
            <button type="submit" class="btn">Apply Filters</button>
        </div>
    </form>
</section>

<section class="section">
    <?php if (empty($records)): ?>
        <div class="empty-state">No attendance records found for the selected filters.</div>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Recorded At</th>
                        <th>Student</th>
                        <th>Student ID</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $record): ?>
                        <tr>
                            <td><span data-attendance-time="<?= e($record['recorded_at']); ?>"></span></td>
                            <td><?= e($record['full_name']); ?></td>
                            <td><?= e($record['student_identifier']); ?></td>
                            <td><?= e($record['email']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

