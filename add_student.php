<?php
require_once __DIR__ . '/partials/header.php';

?>

<section class="section">
    <div class="section-header">
        <h2>Add Student</h2>
    </div>

    <form action="save_student.php" method="post" autocomplete="off">
        <div class="form-group">
            <label for="student_identifier">Student ID</label>
            <input type="text" id="student_identifier" name="student_identifier" required placeholder="e.g. STU-00123">
        </div>

        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name" required placeholder="e.g. Taylor Jackson">
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required placeholder="e.g. taylor@example.com">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn">Create &amp; Generate QR</button>
            <a class="btn btn-secondary" href="students.php">Back to Students</a>
        </div>
    </form>
</section>

<?php require_once __DIR__ . '/partials/footer.php'; ?>

