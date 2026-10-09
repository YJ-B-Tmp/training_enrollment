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
    }

    public function create($course_id, $code, $schedule, $instructor, $slots)
    {
        // TODO: insert a class section
// FILL IN CODE HERE
    }

    public function getSlots($class_id)
    {
        // TODO: return the remaining slots for a class
// FILL IN CODE HERE
    }
}