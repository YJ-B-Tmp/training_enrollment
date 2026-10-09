<?php
$base = '';
require_once __DIR__ . '/includes/header.php';
?>
<h1>Training Enrollment System</h1>
<p>Welcome!</p>
<ul>
  <li><a href='admin/courses.php'>Manage Courses</a></li>
  <li><a href='admin/classes.php'>Manage Classes</a></li>
  <li><a href='admin/students.php'>Record a New Student</a></li>
  <li><a href='admin/enroll.php'>Enroll an Existing Student</a></li>
  <li><a href='admin/enrollments.php'>View / Cancel Enrollments</a></li>
  <li><a href='admin/reports.php'>Reports</a></li>
</ul>
<?php require_once __DIR__ . '/includes/footer.php'; ?>