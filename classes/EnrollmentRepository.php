<?php
// classes/EnrollmentRepository.php
// Data-access class that records students, enrollments and slot updates
// inside a single database transaction.
class EnrollmentRepository
{
    private $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Record a NEW student AND enroll them into a class.
    public function recordStudent($full_name, $email, $phone, $class_id)
    {
        // TODO: perform ALL of the following inside ONE transaction:
// 1. beginTransaction()
// 2. check that the class still has available slots
// 3. INSERT INTO students (full_name, email, phone)
// 4. $student_id = lastInsertId()
// 5. INSERT INTO enrollments (student_id, class_id)
// 6. UPDATE classes SET slots = slots - 1
// 7. commit(); otherwise rollBack();
// Throw or return false when there are no slots left.
// FILL IN CODE HERE
    }

    // Enroll an EXISTING student into a class.
    public function enroll($student_id, $class_id)
    {
        // TODO: inside a transaction:
// 1. check the class still has available slots
// 2. INSERT INTO enrollments (student_id, class_id)
// 3. UPDATE classes SET slots = slots - 1
// FILL IN CODE HERE
    }

    // Cancel an enrollment.
    public function cancel($enrollment_id)
    {
        // TODO: inside a transaction:
// 1. UPDATE enrollments SET status = 'cancelled'
// 2. UPDATE classes SET slots = slots + 1
// FILL IN CODE HERE
    }
    public function allWithDetails()
    {
        // TODO: JOIN enrollments, students, classes and courses
// FILL IN CODE HERE
    }
}