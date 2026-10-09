<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../classes/EnrollmentRepository.php';

$repo = new EnrollmentRepository($db);
$message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enrollment_id = (int) ($_POST['enrollment_id'] ?? 0);
    try {
        if ($repo->cancel($enrollment_id)) {
            $message = 'Enrollment cancelled and slot restored.';
        } else {
            $errors[] = 'Enrollment not found or already cancelled.';
        }
    } catch (Exception $ex) {
        $errors[] = 'Something went wrong. Nothing was changed.';
    }
}

$rows = $repo->allWithDetails();
$base = '../';
require_once __DIR__ . '/../includes/header.php';
?>
<h1>Enrollments</h1>
<?php if ($message): ?><p class='success'><?= e($message) ?></p><?php endif; ?>
<?php foreach ($errors as $err): ?><p class='error'><?= e($err) ?></p><?php endforeach; ?>

<table>
  <tr><th>#</th><th>Student</th><th>Course</th><th>Class</th><th>Schedule</th>
      <th>Instructor</th><th>Slots Left</th><th>Status</th><th>Date</th><th>Action</th></tr>
  <?php foreach ($rows as $r): ?>
  <tr>
    <td><?= (int) $r['enrollment_id'] ?></td>
    <td><?= e($r['full_name']) ?></td>
    <td><?= e($r['course_name']) ?></td>
    <td><?= e($r['class_code']) ?></td>
    <td><?= e($r['schedule']) ?></td>
    <td><?= e($r['instructor']) ?></td>
    <td><?= (int) $r['slots'] ?></td>
    <td><?= e($r['status']) ?></td>
    <td><?= e($r['enrollment_date']) ?></td>
    <td>
      <?php if ($r['status'] === 'active'): ?>
      <form method='post' action='enrollments.php'
            onsubmit="return confirm('Cancel this enrollment?');">
        <input type='hidden' name='enrollment_id' value='<?= (int) $r['enrollment_id'] ?>'>
        <button type='submit'>Cancel</button>
      </form>
      <?php endif; ?>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>