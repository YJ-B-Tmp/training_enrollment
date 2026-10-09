<?php
// admin/enroll.php -- Enroll an EXISTING student into a class
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../classes/EnrollmentRepository.php';

$repo = new EnrollmentRepository($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = (int) ($_POST['student_id'] ?? 0);
    $class_id = (int) ($_POST['class_id'] ?? 0);

    // TODO: validate the ids
    // TODO: call $repo->enroll($student_id, $class_id)
    // TODO: show a success or "no slots available" message
    // FILL IN CODE HERE
}
?>
<!-- TODO: build the enrollment form (student dropdown + class dropdown) -->