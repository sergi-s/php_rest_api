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
$db  = new Database();
$connenction = $db->connect();

//create student object
$user_obj = new Users($connenction);

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->email) && !empty($data->password)) {

        //submit data 
        $user_obj->email = $data->email;
        //$user_obj->password = $data->password;
        //password_hash($data->password, PASSWORD_DEFAULT);

        $user_data =  $user_obj->check_login();
        if (!empty($user_data)) {
            $name = $user_data['name'];
            $email = $user_data['email'];
            $password = $user_data['password'];

            if (password_verify($data->password, $password)) {

                $iss = "localhost";
                $iat = time();
                $nbf = $iat + 10;
                $exp = $iat + 60000;
                $aud = "myusers";
                $user_arr_data = array(
                    "id" => $user_data['id'],
                    "name" => $user_data['name'],
                    "eamil" => $user_data['email'],
                );

                $secret_key = "owt1123";

                $payload_info = array(
                    "iss" => $iss,
                    "iat" => $iat,
                    "nbf" => $nbf,
                    "exp" => $exp,
                    "aud" => $aud,
                    "data" => $user_arr_data
                );

                $jwt = JWT::encode($payload_info, $secret_key);
                http_response_code(200);
                echo json_encode(array(
                    "status" => 1,
                    "jwt" => $jwt,
                    "message" => "User logged-in successfully"
                ));
            } else {
                http_response_code(500); //page not found 
                echo json_encode(array("status" => 0, "message" => "Invalid credintials"));
            }
        } else {
            //some data we have
            //this email does not existe
            http_response_code(404);
            echo json_encode(array("status" => 0, "message" => "User not found"));
        }
    } else {
        http_response_code(404); //page not found 
        echo json_encode(array("status" => 0, "message" => "All values needed"));
    }
} else {
    http_response_code(503); // services unavilable
    echo json_encode(array("status" => 0, "message" => "Access dined"));
}
