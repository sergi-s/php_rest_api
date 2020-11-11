<?php
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
$db  = new Database();
$connenction = $db->connect();

//create student object
$user_obj = new Users($connenction);

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $all_headers = getallheaders();
    $data->jwt = $all_headers['Authorization'];
    //$data = json_decode(file_get_contents("php://input"));
    if (!empty($data->jwt)) {
        $secret_key = "owt1123";
        try {
            $decoded_data = JWT::decode($data->jwt, $secret_key, array("HS256"));
            http_response_code(200);
            echo json_encode(array(
                "status" => 1,
                "message" => "We got the JWT token",
                "Data" => $decoded_data
            ));
        } catch (Exception $ex) {
            http_response_code(500); //internal server error
            echo json_encode(array(
                "status" => 0,
                "message" => $ex->getMessage(),
            ));
        };
    }
}
