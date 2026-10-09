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
        try {
            $this->db->beginTransaction();

            // Check available slots
            $stmt = $this->db->prepare('SELECT slots FROM classes WHERE id = :class_id FOR UPDATE');
            $stmt->execute(['class_id' => $class_id]);
            $class = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$class || $class['class_slots'] <= 0) {
                throw new Exception('No slots available');
            }

            // Insert student
            $stmt = $this->db->prepare('INSERT INTO students (full_name, email, phone) VALUES (:full_name, :email, :phone)');
            $stmt->execute(['full_name' => $full_name, 'email' => $email, 'phone' => $phone]);
            $student_id = $this->db->lastInsertId();

            // Insert enrollment
            $stmt = $this->db->prepare('INSERT INTO enrollments (student_id, class_id) VALUES (:student_id, :class_id)');
            $stmt->execute(['student_id' => $student_id, 'class_id' => $class_id]);

            // Update class slots
            $stmt = $this->db->prepare('UPDATE classes SET slots = lots - 1 WHERE id = :class_id');
            $stmt->execute(['class_id' => $class_id]);

            // Commit transaction
            $this->db->commit();
        } catch (Exception $e) {
            // Rollback transaction on error
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e; // Re-throw the exception for further handling
        }
    }

    // Enroll an EXISTING student into a class.
    public function enroll($student_id, $class_id)
    {
        // TODO: inside a transaction:
// 1. check the class still has available slots
// 2. INSERT INTO enrollments (student_id, class_id)
// 3. UPDATE classes SET slots = slots - 1
// FILL IN CODE HERE
        try{
            $this->db->beginTransaction();
            $stmt = $this->db->prepare('SELECT slots FROM classes WHERE id = :class_id FOR UPDATE');
            $stmt->execute(['class_id' => $class_id]);
            $class = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$class || $class['slots'] <= 0) {
                throw new Exception('No slots available');
            }

            $stmt = $this->db->prepare('INSERT INTO enrollments (student_id, class_id) VALUES (:student_id, :class_id)');
            $stmt->execute(['student_id' => $student_id, 'class_id' => $class_id]);

            $stmt = $this->db->prepare('UPDATE classes SET slots = slots - 1 WHERE id = :class_id');
            $stmt->execute(['class_id' => $class_id]);

            $this->db->commit();
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    // Cancel an enrollment.
    public function cancel($enrollment_id)
    {
        // TODO: inside a transaction:
// 1. UPDATE enrollments SET status = 'cancelled'
// 2. UPDATE classes SET slots = slots + 1
// FILL IN CODE HERE
        try {
            $this->db->beginTransaction();

            // Update enrollment status
            $stmt = $this->db->prepare('UPDATE enrollments SET status = "cancelled" WHERE id = :enrollment_id');
            $stmt->execute(['enrollment_id' => $enrollment_id]);

            // Get class_id for the enrollment
            $stmt = $this->db->prepare('SELECT class_id FROM enrollments WHERE id = :enrollment_id');
            $stmt->execute(['enrollment_id' => $enrollment_id]);
            $class = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($class) {
                // Update class slots
                $stmt = $this->db->prepare('UPDATE classes SET slots = slots + 1 WHERE id = :class_id');
                $stmt->execute(['class_id' => $class['class_id']]);
            }

            $this->db->commit();
        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }
    public function allWithDetails()
    {
        // TODO: JOIN enrollments, students, classes and courses
// FILL IN CODE HERE
        $stmt = $this->db->query('
            SELECT e.id AS enrollment_id, s.full_name, s.email, s.phone, c.class_code, c.class_schedule, c.class_instructor, co.course_name
            FROM enrollments e
            JOIN students s ON e.student_id = s.id
            JOIN classes c ON e.class_id = c.id
            JOIN courses co ON c.course_id = co.id
        ');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}