<?php 
// includes/header.php
$base_url = "/training_enrollment/";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Training Enrollment System</title>
    <link rel='stylesheet' href='<?= $base_url ?>css/style.css'>
</head>

<body>
    <nav>
        <a href="<?= $base_url ?>index.php">Home</a> |
        <a href="<?= $base_url ?>admin/courses.php">Courses</a> |
        <a href="<?= $base_url ?>admin/classes.php">Classes</a> |
        <a href="<?= $base_url ?>admin/students.php">Record Student</a> |
        <a href="<?= $base_url ?>admin/enroll.php">Enroll</a> |
        <a href="<?= $base_url ?>admin/enrollments.php">Enrollments</a>
    </nav>
    <main>