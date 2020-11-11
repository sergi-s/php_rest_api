<?php
ini_set("display_error", 1);
// include headers
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Methods: GET");

//include database.php and the student.php
include_once("../config/database.php");
include_once("../classes/user.php");

//create object for database
$db = new Database();
$connenction = $db->connect();

//create student object
$user_obj = new Users($connenction);

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $projects = $user_obj->get_all_projects();
    if ($projects->num_rows > 0) {
        $projects_arr = array();
        while ($row = $projects->fetch_assoc()) {
            $projects_arr[] = array(
                "id" => $row['id'],
                "name" => $row['name'],
                "description" => $row['description'],
                "user_id" => $row["user_id"],
                "status" => $row['status'],
                "created_at" => $row['created_at']
            );
        }

        http_response_code(200);
        echo json_encode(array(
            "status" => 1,
            "Projects" => $projects_arr
        ));
    } else {
        http_response_code(404);
        echo json_encode(array(
            "status" => 0,
            "message" => "No Projects found"
        ));
    }
}
