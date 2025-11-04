<?php
require_once __DIR__ . '/partials/header.php';

$pdo = getPDO();

try {
    $students = $pdo->query('SELECT id, student_identifier, full_name, email, qr_token, created_at FROM students ORDER BY created_at DESC')->fetchAll();
} catch (PDOException $exception) {
    $students = [];
}

?>

<section class="section">
    <div class="section-header">
        <h2>Students</h2>
        <a class="btn" href="add_student.php">Add New</a>
    </div>

    <?php if (empty($students)): ?>
        <div class="empty-state">
            There are no students yet. Add one to generate their QR code.
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Student ID</th>
                        <th>Email</th>
                        <th>QR Token</th>
                        <th>QR Code</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?= e($student['full_name']); ?></td>
                            <td><?= e($student['student_identifier']); ?></td>
                            <td><?= e($student['email']); ?></td>
                            <td>
                                <div class="table-actions">
                                    <code><?= e($student['qr_token']); ?></code>
                                    <button type="button" class="btn btn-secondary" data-copy="<?= e($student['qr_token']); ?>">Copy Token</button>
                                </div>
                            </td>
                            <td>
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&amp;data=<?= urlencode($student['qr_token']); ?>" alt="QR Code for <?= e($student['full_name']); ?>" width="140" height="140">
                            </td>
                            <td><span data-attendance-time="<?= e($student['created_at']); ?>"></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

