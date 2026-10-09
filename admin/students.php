<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../classes/EnrollmentRepository.php';
require_once __DIR__ . '/../classes/ClassSection.php';

$repo = new EnrollmentRepository($db);
$classes = new ClassSection($db);
$classList = $classes->allWithCourse();
$message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');
    $class_id  = (int) ($_POST['class_id'] ?? 0);

    if ($full_name === '' || strlen($full_name) > 100) {
        $errors[] = 'Full name is required (max 100 characters).';
    }
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email address is not valid.';
    }
    if ($phone !== '' && !preg_match('/^[0-9+() -]{5,30}$/', $phone)) {
        $errors[] = 'Phone may contain only digits, + ( ) and dashes.';
    }
    if ($class_id <= 0 || $classes->getSlots($class_id) === false) {
        $errors[] = 'Please choose a valid class.';
    }

    if (!$errors) {
        try {
            if ($repo->recordStudent($full_name, $email, $phone, $class_id)) {
                $message = 'Student recorded and enrolled successfully!';
            } else {
                $errors[] = 'No slots available in that class. Nothing was saved.';
            }
        } catch (Exception $ex) {
            $errors[] = 'Something went wrong. Nothing was saved.';
        }
    }
    $classList = $classes->allWithCourse(); // refresh slot counts
}

$base = '../';
require_once __DIR__ . '/../includes/header.php';
?>
<h1>Record Student</h1>
<?php if ($message): ?><p class='success'><?= e($message) ?></p><?php endif; ?>
<?php foreach ($errors as $err): ?><p class='error'><?= e($err) ?></p><?php endforeach; ?>

<form method='post' action='students.php'>
  <label>Full Name</label>
  <br><input type='text' name='full_name' placeholder="Firstname Lastname"><br><br>
  <label>Email</label>
  <br><input type='text' name='email' placeholder="student@email.com"><br><br>
  <label>Phone</label>
  <br><input type='text' name='phone' placeholder="(+63) 915-333-4444"><br><br>
  <label>Class</label><br>
  <select name='class_id'>
    <?php foreach ($classList as $c): ?>
      <option value='<?= (int) $c['class_id'] ?>'>
        <?= e($c['course_name'] . ' - ' . $c['class_code'] . ' (' . $c['slots'] . ' slots left)') ?>
      </option>
    <?php endforeach; ?>
  </select><br><br>
  <button type='submit'>Record Student</button>
</form><br>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>