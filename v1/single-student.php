<?php
// include headers
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET");

//include database.php and the student.php
include_once("../config/database.php");
include_once("../classes/student.php");

//create object for database
$db  = new Database();
$connenction = $db->connect();

//create student object
$student = new Student($connenction);

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $student_id = isset($_GET['id']) ? intval($_GET['id']) : "";
    if (!empty($student_id)) {
        $student->id = $student_id;
        $data =  $student->get_single_student();
        // print_r($data);
        if (!empty($data)) {
            http_response_code(200);
            echo json_encode(array(
                "status" => 0,
                "Data" => $data
            ));
        } else {
            http_response_code(404);
            echo json_encode(array(
                "status" => 1,
                "message" => "Student Not found"
            ));
        }
    }
} else {
    http_response_code(503); //service unavilable
    echo json_encode(array(
        "Status" => 0,
        "message" => "Access denined"
    ));
}
