<?php
class ClassSection {
    private $db;
    public function __construct(PDO $db) { $this->db = $db; }

    // JOIN: each class row also shows its course name
    public function allWithCourse() {
        return $this->db->query(
            'SELECT c.*, co.course_code, co.course_name
             FROM classes c
             JOIN courses co ON c.course_id = co.course_id
             ORDER BY co.course_name, c.class_code')->fetchAll();
    }

    public function create($course_id, $code, $schedule, $instructor, $slots) {
        $stmt = $this->db->prepare(
            'INSERT INTO classes (course_id, class_code, schedule, instructor, slots)
             VALUES (:course_id, :code, :schedule, :instructor, :slots)');
        $stmt->execute([':course_id' => $course_id, ':code' => $code,
                        ':schedule' => $schedule, ':instructor' => $instructor,
                        ':slots' => $slots]);
        return $this->db->lastInsertId();
    }

    public function getSlots($class_id) {
        $stmt = $this->db->prepare('SELECT slots FROM classes WHERE class_id = :id');
        $stmt->execute([':id' => $class_id]);
        return $stmt->fetchColumn(); // false if class doesn't exist
    }
}