<?php
// classes/Course.php
class Course
{
    private $db;
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function all()
    {
        // TODO: return all courses ordered by course_name
// FILL IN CODE HERE
    }

    public function find($id)
    {
        // TODO: return one course by id (prepared statement)
// FILL IN CODE HERE
    }

    public function create($code, $name, $description)
    {
        // TODO: insert a course
        // FILL IN CODE HERE
    }

    public function update($id, $code, $name, $description)
    {
        // TODO: update a course
        // FILL IN CODE HERE
    }

    public function delete($id)
    {
        // TODO: delete a course
        // FILL IN CODE HERE
    }
}