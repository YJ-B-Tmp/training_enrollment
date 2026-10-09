<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../classes/Course.php';
require_once __DIR__ . '/../classes/ClassSection.php';

$courseObj = new Course($db);
$classObj  = new ClassSection($db);
$courses   = $courseObj->all();
$message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id  = (int) ($_POST['course_id'] ?? 0);
    $code       = trim($_POST['class_code'] ?? '');
    $schedule   = trim($_POST['schedule'] ?? '');
    $instructor = trim($_POST['instructor'] ?? '');
    $slots      = $_POST['slots'] ?? '';

    if (!$courseObj->find($course_id)) { $errors[] = 'Please choose a valid course.'; }
    if ($code === '' || strlen($code) > 20) { $errors[] = 'Class code is required (max 20 characters).'; }
    if ($instructor === '') { $errors[] = 'Instructor is required.'; }
    if (!ctype_digit((string) $slots)) { $errors[] = 'Slots must be a whole number (0 or more).'; }

    if (!$errors) {
        $classObj->create($course_id, $code, $schedule, $instructor, (int) $slots);
        $message = 'Class added.';
    }
}

$classList = $classObj->allWithCourse();
$base = '../';
require_once __DIR__ . '/../includes/header.php';
?>
<h1>Classes</h1>
<?php if ($message): ?><p class='success'><?= e($message) ?></p><?php endif; ?>
<?php foreach ($errors as $err): ?><p class='error'><?= e($err) ?></p><?php endforeach; ?>

<h2>Add Class</h2>
<form method='post' action='classes.php'>
  <label>Course</label>
  <select name='course_id'>
    <?php foreach ($courses as $c): ?>
      <option value='<?= (int) $c['course_id'] ?>'><?= e($c['course_name']) ?></option>
    <?php endforeach; ?>
  </select>
  <label>Class Code</label><input type='text' name='class_code'>
  <label>Schedule</label><input type='text' name='schedule'>
  <label>Instructor</label><input type='text' name='instructor'>
  <label>Slots</label><input type='number' name='slots' min='0' value='10'>
  <button type='submit'>Add Class</button>
</form>

<table>
  <tr><th>Course</th><th>Class</th><th>Schedule</th><th>Instructor</th><th>Slots Left</th></tr>
  <?php foreach ($classList as $c): ?>
  <tr>
    <td><?= e($c['course_name']) ?></td>
    <td><?= e($c['class_code']) ?></td>
    <td><?= e($c['schedule']) ?></td>
    <td><?= e($c['instructor']) ?></td>
    <td><?= (int) $c['slots'] ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>