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
if (empty($full_name) || empty($email) || empty($phone) || empty($class_id)) {
    $error = "Please fill in all fields.";
} else {
    try {
        $repo->recordStudent($full_name, $email, $phone, $class_id);
        $success = "Student enrolled successfully!";
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

$classList = $classes->allWithCourse();
$base='../';
require_once __DIR__ . '/../includes/header.php';
?>
<!-- TODO: build the form: full_name, email, phone, class dropdown -->
 <h1>Student Recording Form</h1>
 <form method="POST">
    <label for="full_name">Full Name:</label>
    <input type="text" name="full_name" id="full_name" required><br>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required><br>

    <label for="phone">Phone:</label>
    <input type="text" name="phone" id="phone" required><br>

    <label for="class_id">Class:</label>
    <select name="class_id" id="class_id" required>
        <option value="">Select a class</option>
        <?php foreach ($classList as $class): ?>
            <option value="<?php echo $class['id']; ?>">
                <?php echo htmlspecialchars($class['course_name'] . ' - ' . $class['class_code']); ?>
            </option>
        <?php endforeach; ?>
    </select><br>

    <button type="submit">Enroll Student</button>
 </form>