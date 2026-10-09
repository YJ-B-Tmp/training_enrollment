<?php
// admin/students.php -- Student recording form
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../classes/EnrollmentRepository.php';
require_once __DIR__ . '/../classes/ClassSection.php';

$repo = new EnrollmentRepository($db);
$classes = new ClassSection($db);
$classList = $classes->allWithCourse(); // for the class dropdown

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$class_id = (int) ($_POST['class_id'] ?? 0);
}
// TODO: validate inputs (non-empty name, valid class_id, ...)
// TODO: call $repo->recordStudent($full_name, $email, $phone, $class_id)
// That single call must INSERT the student, INSERT the enrollment
// and decrement the class slots inside ONE transaction.
// TODO: show a success message, or "no slots available" on failure
// FILL IN CODE HERE
?>
<!-- TODO: build the form: full_name, email, phone, class dropdown -->