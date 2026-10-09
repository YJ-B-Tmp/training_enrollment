<?php
// admin/enrollments.php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../classes/EnrollmentRepository.php';

$repo = new EnrollmentRepository($db);

// TODO: fetch all enrollments with class and course details
$rows = null; // FILL IN CODE HERE
?>
<!-- TODO: render $rows in an HTML table -->
<!-- TODO: add a "Cancel" link that calls cancel($enrollment_id) -->