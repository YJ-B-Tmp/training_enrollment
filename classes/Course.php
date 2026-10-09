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

        return $this->db->query("SELECT * FROM courses ORDER BY course_name")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        // TODO: return one course by id (prepared statement)
        $stmt = $this->db->prepare('SELECT * FROM courses WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($code, $name, $description)
    {
        // TODO: insert a course
        $stmt = $this->db->prepare('INSERT INTO courses (course_code, course_name, course_description) VALUES (:code, :name, :description)');
        return $stmt -> execute(['code'=> $code,'name'=> $name,'description'=> $description]);
    }

    public function update($id, $code, $name, $description)
    {
        // TODO: update a course
        $stmt = $this->db->prepare('UPDATE courses SET course_code = :code, course_name = :name, course_description = :description WHERE id = :id');
        return $stmt->execute(['id' => $id, 'code' => $code, 'name' => $name, 'description' => $description]);
    }

    public function delete($id)
    {
        // TODO: delete a course
        $stmt = $this->db->prepare('DELETE FROM courses WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}