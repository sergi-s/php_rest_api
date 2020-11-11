<?php
// include headers
header("Access-Control-Allow-Origin:*");
header("Content-type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

//include database.php and the student.php
include_once("../config/database.php");
include_once("../classes/user.php");

//create object for database
$db  = new Database();
$connenction = $db->connect();

//create student object
$user_obj = new Users($connenction);

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->name) && !empty($data->email) && !empty($data->password)) {

        //submit data 
        $user_obj->name = $data->name;
        $user_obj->email = $data->email;
        $user_obj->password = password_hash($data->password, PASSWORD_DEFAULT);

        $email_data =  $user_obj->check_email();
        if (!empty($email_data)) {
            //some data we have
            //this email is used
            http_response_code(400); //bad request 
            echo json_encode(array("status" => 0, "message" => "This email is already in use"));
        } else {
            if ($user_obj->create_user()) {
                http_response_code(200); //ok
                echo json_encode(array("status" => 1, "message" => "User has being created"));
            } else {
                http_response_code(500); // internal server error
                echo json_encode(array("status" => 0, "message" => "failed to insert data"));
            }
        }
    } else {
        http_response_code(404); //page not found 
        echo json_encode(array("status" => 0, "message" => "All values needed"));
    }
} else {
    http_response_code(503); // services unavilable
    echo json_encode(array("status" => 0, "message" => "Access dined"));
}
