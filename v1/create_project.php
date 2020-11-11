<?php
//ini_set("display_error", 1);
require "../vendor/autoload.php";

use \Firebase\JWT\JWT;

// include headers
header("Access-Control-Allow-Origin:*");
header("Content-type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

//include database.php and the student.php
include_once("../config/database.php");
include_once("../classes/user.php");

//create object for database
$db = new Database();
$connenction = $db->connect();

//create student object
$user_obj = new Users($connenction);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    //body 
    $data = json_decode(file_get_contents("php://input"));
    $all_headers = getallheaders();
    if (!empty($data->name) && !empty($data->description) && !empty($data->status)) {
        try {
            $jwt = $all_headers['Authorization'];
            $secret_key = "owt1123";
            $decoded_data = JWT::decode($jwt, $secret_key, array("HS256"));

            $user_obj->user_id = $decoded_data->data->id;
            $user_obj->product_name = $data->name;
            $user_obj->product_description = $data->description;
            $user_obj->product_status = $data->status;
            if ($user_obj->create_project()) {
                http_response_code(200);
                echo json_encode(array(
                    "status" => 1,
                    "message" => "project has been created"
                ));
            } else {
                http_response_code(500);
                echo json_encode(array(
                    "status" => 0,
                    "message" => "Failed to create project"
                ));
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(array(
                "status" => 0,
                "message" => $e->getMessage()
            ));
        }
    } else {
        http_response_code(404);
        echo json_encode(array(
            "status" => 0,
            "message" => "All data needed"
        ));
    }
}
