<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../classes/Course.php';

$courseObj = new Course($db);
$message = '';
$errors = [];
$edit = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = (int) ($_POST['course_id'] ?? 0);

    if ($action === 'delete') {
        try {
            $courseObj->delete($id);
            $message = 'Course deleted.';
        } catch (PDOException $ex) {
            $errors[] = 'Cannot delete: this course still has classes.';
        }
    } else {
        $code = trim($_POST['course_code'] ?? '');
        $name = trim($_POST['course_name'] ?? '');
        $desc = trim($_POST['description'] ?? '');

        if ($code === '' || strlen($code) > 20) {
            $errors[] = 'Course code is required (max 20 characters).';
        }
        if ($name === '' || strlen($name) > 100) {
            $errors[] = 'Course name is required (max 100 characters).';
        }

        if (!$errors) {
            try {
                if ($action === 'update') {
                    $courseObj->update($id, $code, $name, $desc);
                    $message = 'Course updated.';
                } else {
                    $courseObj->create($code, $name, $desc);
                    $message = 'Course added.';
                }
            } catch (PDOException $ex) {
                $errors[] = 'That course code already exists.';
            }
        }
    }
}

if (isset($_GET['edit'])) {
    $edit = $courseObj->find((int) $_GET['edit']);
}
$courses = $courseObj->all();

$base = '../';
require_once __DIR__ . '/../includes/header.php';
?>
<h1>Courses</h1>

<?php if ($message): ?><p class='success'><?= e($message) ?></p><?php endif; ?>
<?php foreach ($errors as $err): ?><p class='error'><?= e($err) ?></p><?php endforeach; ?>

<h2><?= $edit ? 'Edit Course' : 'Add Course' ?></h2>
<form method='post' action='courses.php'>
  <input type='hidden' name='action' value='<?= $edit ? 'update' : 'create' ?>'>
  <input type='hidden' name='course_id' value='<?= e($edit['course_id'] ?? 0) ?>'>
  <label>Course Code</label>
  <input type='text' name='course_code' value='<?= e($edit['course_code'] ?? '') ?>'>
  <label>Course Name</label>
  <input type='text' name='course_name' value='<?= e($edit['course_name'] ?? '') ?>'>
  <label>Description</label>
  <textarea name='description'><?= e($edit['description'] ?? '') ?></textarea>
  <button type='submit'><?= $edit ? 'Save Changes' : 'Add Course' ?></button>
</form>

<table>
  <tr><th>Code</th><th>Name</th><th>Description</th><th>Actions</th></tr>
  <?php foreach ($courses as $c): ?>
  <tr>
    <td><?= e($c['course_code']) ?></td>
    <td><?= e($c['course_name']) ?></td>
    <td><?= e($c['description']) ?></td>
    <td>
      <a href='courses.php?edit=<?= (int) $c['course_id'] ?>'>Edit</a>
      <form method='post' action='courses.php' style='display:inline'
            onsubmit="return confirm('Delete this course?');">
        <input type='hidden' name='action' value='delete'>
        <input type='hidden' name='course_id' value='<?= (int) $c['course_id'] ?>'>
        <button type='submit'>Delete</button>
      </form>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>