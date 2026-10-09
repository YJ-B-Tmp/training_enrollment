<?php
// classes/EnrollmentRepository.php
class EnrollmentRepository {
    private $db;
    public function __construct(PDO $db) { $this->db = $db; }

    // Lock the class row and return its slots (false if class not found)
    private function lockSlots($class_id) {
        $stmt = $this->db->prepare(
            'SELECT slots FROM classes WHERE class_id = :id FOR UPDATE');
        $stmt->execute([':id' => $class_id]);
        return $stmt->fetchColumn();
    }

    // new student
    public function recordStudent($full_name, $email, $phone, $class_id) {
        try {
            $this->db->beginTransaction();

            $slots = $this->lockSlots($class_id);
            if ($slots === false || $slots <= 0) {
                $this->db->rollBack();
                return false;                    // no slots left
            }

            $stmt = $this->db->prepare(
                'INSERT INTO students (full_name, email, phone)
                 VALUES (:n, :e, :p)');
            $stmt->execute([':n' => $full_name, ':e' => $email, ':p' => $phone]);
            $student_id = $this->db->lastInsertId();

            $stmt = $this->db->prepare(
                'INSERT INTO enrollments (student_id, class_id) VALUES (:s, :c)');
            $stmt->execute([':s' => $student_id, ':c' => $class_id]);

            $stmt = $this->db->prepare(
                'UPDATE classes SET slots = slots - 1 WHERE class_id = :c');
            $stmt->execute([':c' => $class_id]);

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $ex;
        }
    }

    // existing
    public function enroll($student_id, $class_id) {
        try {
            $this->db->beginTransaction();

            $slots = $this->lockSlots($class_id);
            if ($slots === false || $slots <= 0) {
                $this->db->rollBack();
                return 'no_slots';
            }

            // duplicate check
            $stmt = $this->db->prepare(
                'SELECT COUNT(*) FROM enrollments
                 WHERE student_id = :s AND class_id = :c AND status = :st');
            $stmt->execute([':s' => $student_id, ':c' => $class_id, ':st' => 'active']);
            if ($stmt->fetchColumn() > 0) {
                $this->db->rollBack();
                return 'duplicate';
            }

            $stmt = $this->db->prepare(
                'INSERT INTO enrollments (student_id, class_id) VALUES (:s, :c)');
            $stmt->execute([':s' => $student_id, ':c' => $class_id]);

            $stmt = $this->db->prepare(
                'UPDATE classes SET slots = slots - 1 WHERE class_id = :c');
            $stmt->execute([':c' => $class_id]);

            $this->db->commit();
            return 'ok';
        } catch (Exception $ex) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $ex;
        }
    }

    // Cancel an enrollment and give the slot back (one transaction)
    public function cancel($enrollment_id) {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare(
                'SELECT class_id, status FROM enrollments
                 WHERE enrollment_id = :id FOR UPDATE');
            $stmt->execute([':id' => $enrollment_id]);
            $row = $stmt->fetch();

            if (!$row || $row['status'] !== 'active') {
                $this->db->rollBack();
                return false;                    // missing or already cancelled
            }

            $stmt = $this->db->prepare(
                'UPDATE enrollments SET status = :st WHERE enrollment_id = :id');
            $stmt->execute([':st' => 'cancelled', ':id' => $enrollment_id]);

            $stmt = $this->db->prepare(
                'UPDATE classes SET slots = slots + 1 WHERE class_id = :c');
            $stmt->execute([':c' => $row['class_id']]);

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $ex;
        }
    }

    public function allWithDetails() {
        return $this->db->query(
            'SELECT e.enrollment_id, e.enrollment_date, e.status,
                    s.full_name, s.email,
                    c.class_code, c.schedule, c.instructor, c.slots,
                    co.course_name
             FROM enrollments e
             JOIN students s  ON e.student_id = s.student_id
             JOIN classes c   ON e.class_id   = c.class_id
             JOIN courses co  ON c.course_id  = co.course_id
             ORDER BY e.enrollment_id DESC')->fetchAll();
    }
}