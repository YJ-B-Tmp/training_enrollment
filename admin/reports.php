<?php
require_once __DIR__ . '/../config/db.php';

$perClass = $db->getRows(
    'SELECT co.course_name, c.class_code, c.slots AS slots_left,
            COUNT(e.enrollment_id) AS active_enrolled
     FROM classes c
     JOIN courses co ON c.course_id = co.course_id
     LEFT JOIN enrollments e ON e.class_id = c.class_id AND e.status = :st
     GROUP BY c.class_id, co.course_name, c.class_code, c.slots
     ORDER BY co.course_name, c.class_code',
    [':st' => 'active']);

$base = '../';
require_once __DIR__ . '/../includes/header.php';
?>
<h1>Reports</h1>
<h2>Enrollment Summary per Class</h2>
<table>
  <tr><th>Course</th><th>Class</th><th>Active Enrolled</th><th>Slots Left</th></tr>
  <?php foreach ($perClass as $r): ?>
  <tr>
    <td><?= e($r['course_name']) ?></td>
    <td><?= e($r['class_code']) ?></td>
    <td><?= (int) $r['active_enrolled'] ?></td>
    <td><?= (int) $r['slots_left'] ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>