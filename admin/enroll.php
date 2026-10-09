<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../classes/EnrollmentRepository.php';
require_once __DIR__ . '/../classes/Student.php';
require_once __DIR__ . '/../classes/ClassSection.php';

$repo = new EnrollmentRepository($db);
$studentObj = new Student($db);
$classObj = new ClassSection($db);
$message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = (int) ($_POST['student_id'] ?? 0);
    $class_id   = (int) ($_POST['class_id'] ?? 0);

    if (!$studentObj->find($student_id)) { $errors[] = 'Please choose a valid student.'; }
    if ($classObj->getSlots($class_id) === false) { $errors[] = 'Please choose a valid class.'; }

    if (!$errors) {
        try {
            $result = $repo->enroll($student_id, $class_id);
            if ($result === 'ok')             { $message = 'Student enrolled successfully!'; }
            elseif ($result === 'duplicate')  { $errors[] = 'That student is already enrolled in this class.'; }
            else                              { $errors[] = 'No slots available in that class.'; }
        } catch (Exception $ex) {
            $errors[] = 'Something went wrong. Nothing was saved.';
        }
    }
}

$students = $studentObj->all();
$classList = $classObj->allWithCourse();
$base = '../';
require_once __DIR__ . '/../includes/header.php';
?>
<h1>Enroll Existing Student</h1>
<?php if ($message): ?><p class='success'><?= e($message) ?></p><?php endif; ?>
<?php foreach ($errors as $err): ?><p class='error'><?= e($err) ?></p><?php endforeach; ?>

<form method='post' action='enroll.php'>
  <label>Student</label>
  <select name='student_id'>
    <?php foreach ($students as $s): ?>
      <option value='<?= (int) $s['student_id'] ?>'><?= e($s['full_name']) ?></option>
    <?php endforeach; ?>
  </select>
  <label>Class</label>
  <select name='class_id'>
    <?php foreach ($classList as $c): ?>
      <option value='<?= (int) $c['class_id'] ?>'>
        <?= e($c['course_name'] . ' - ' . $c['class_code'] . ' (' . $c['slots'] . ' slots left)') ?>
      </option>
    <?php endforeach; ?>
  </select>
  <button type='submit'>Enroll</button>
</form>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>