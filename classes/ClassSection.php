<?php
// classes/ClassSection.php
class ClassSection
{
    private $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function allWithCourse()
    {
        // TODO: JOIN classes with courses so each row shows the course name
// // Hint: SELECT c.*, co.course_name FROM classes c
// JOIN courses co ON c.course_id = co.course_id
// FILL IN CODE HERE
        return $this->db->query('SELECT c.*, co.course_name FROM classes c JOIN courses co ON c.course_id = co.course_id')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($course_id, $code, $schedule, $instructor, $slots)
    {
        // TODO: insert a class section
// FILL IN CODE HERE
        $stmt = $this->db->prepare('INSERT INTO classes (course_id, class_code, class_schedule, class_instructor, class_slots) VALUES (:course_id, :code, :schedule, :instructor, :slots)');
        return $stmt->execute(['course_id' => $course_id, 'code' => $code, 'schedule' => $schedule, 'instructor' => $instructor, 'slots' => $slots]);
    }

    public function getSlots($class_id)
    {
        // TODO: return the remaining slots for a class
// FILL IN CODE HERE
        $stmt = $this->db->prepare('SELECT class_slots FROM classes WHERE id = :id');
        $stmt->execute(['id' => $class_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}