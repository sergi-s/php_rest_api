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
    $data =  $student->get_all_data();
    if ($data->num_rows > 0) {
        //we have some data insde the table
        $students["records"] = array();
        while ($row = $data->fetch_assoc()) {
            array_push($students["records"], array(
                "id" => $row['id'],
                "name" => $row['name'],
                "email" => $row['email'],
                "mobile" => $row['mobile'],
                "status" => $row['status'],
                "created_at" => date("y-m-d", strtotime($row['created_at']))
            ));
        }
        http_response_code(200);
        echo json_encode(array(
            "Status" => 1,
            "data" => $students["records"]
        ));
    }
} else {
    http_response_code(503); //service unavailable
    echo json_encode(array("status" => 0, "message" => "Acess Denied"));
}
