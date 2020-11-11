<?php
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

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->name) && !empty($data->email) && !empty($data->mobile)) {

        //submit data 
        $student->name = $data->name;
        $student->email = $data->email;
        $student->mobile = $data->mobile;

        if ($student->create_data()) {
            http_response_code(200); //ok
            echo json_encode(array("status" => 1, "message" => "student has being created"));
        } else {
            http_response_code(500); // internal server error
            echo json_encode(array("status" => 0, "message" => "failed to insert data"));
        }
    } else {
        http_response_code(404); //page not found 
        echo json_encode(array("status" => 0, "message" => "All values needed"));
    }
} else {
    http_response_code(503); // services unavilable
    echo json_encode(array("status" => 0, "message" => "Access dined"));
}
