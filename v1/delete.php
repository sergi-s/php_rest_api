<?php
ini_set("display_errors", 1);
// include headers
header("Access-Control-Allow-Origin:*");
header("Content-type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

//include database.php and the student.php
include_once("../config/database.php");
include_once("../classes/student.php");

//create object for database
$db  = new Database();
$connenction = $db->connect();

//create student object
$student = new Student($connenction);

if ($_SERVER['REQUEST_METHOD'] === "DELETE") {

    $student_id = isset($_GET['id']) ? $_GET['id'] : "";
    if (!empty($student_id)) {
        //submit data 
        $student->id = $student_id;
        if ($student->delete_student()) {
            http_response_code(200); //ok
            echo json_encode(array("status" => 1, "message" => "student has being delteted"));
        } else {
            http_response_code(500); // internal server error
            echo json_encode(array("status" => 0, "message" => "failed to delete data"));
        }
    } else {
        http_response_code(404); //page not found 
        echo json_encode(array("status" => 0, "message" => "All values needed"));
    }
} else {
    http_response_code(503); // services unavilable
    echo json_encode(array("status" => 0, "message" => "Access dined"));
}
