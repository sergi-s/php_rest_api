<?php

class Users
{
    public $name;
    public $email;
    public $password;
    public $user_id;
    public $product_name;
    public $product_description;
    public $status;

    private $conn;
    private $users_tbl;
    private $projects_tbl;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->users_tbl = "tbl_users";
        $this->projects_tbl = "tbl_projects";
    }

    public function create_user()
    {
        //sql query to insert data
        $user_query = "INSERT INTO " . $this->users_tbl . " SET name = ?, email = ?, password=?";

        //prepare the sql query
        $user_obj = $this->conn->prepare($user_query);

        //sanitize inout variables => removes the extra chars like special symbols 
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        //binding parameters with prepare statement   
        $user_obj->bind_param("sss", $this->name, $this->email, $this->password);

        //execute query
        if ($user_obj->execute()) {
            return true;
        } else return false;
    }

    public function check_email()
    {
        $email_query = "SELECT * FROM " . $this->users_tbl . " WHERE email=?";
        $query_obj = $this->conn->prepare($email_query);
        $this->email = htmlspecialchars(strip_tags(($this->email)));
        $query_obj->bind_param("s", $this->email);
        if ($query_obj->execute()) {
            $data = $query_obj->get_result();

            return $data->fetch_assoc();
        } else {
            return array();
        }
    }

    public function check_login()
    {
        $email_query = "SELECT * FROM " . $this->users_tbl . " WHERE email=?";
        $query_obj = $this->conn->prepare($email_query);
        $this->email = htmlspecialchars(strip_tags(($this->email)));
        $query_obj->bind_param("s", $this->email);
        if ($query_obj->execute()) {
            $data = $query_obj->get_result();

            return $data->fetch_assoc();
        } else {
            return array();
        }
    }
    //create projects
    public function create_project()
    {
        $query = "INSERT INTO " . $this->projects_tbl . " SET user_id=?, name=?, description=?, status=?";
        $obj = $this->conn->prepare($query);
        $this->product_name = htmlspecialchars(strip_tags($this->product_name));
        $this->product_description = htmlspecialchars(strip_tags($this->product_description));
        $this->status = htmlspecialchars(strip_tags($this->product_status));
        $obj->bind_param("isss", $this->user_id, $this->product_name, $this->product_description, $this->status);
        if ($obj->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function get_all_projects()
    {
        $query = "SELECT * FROM " . $this->projects_tbl . " ORDER BY id DESC";
        $obj = $this->conn->prepare($query);
        if ($obj->execute()) {
            return $obj->get_result();
        } else {
            return false;
        }
    }

    public function get_user_projects()
    {
        $query = "SELECT * FROM " . $this->projects_tbl . " WHERE user_id=? ORDER BY id DESC";
        $obj = $this->conn->prepare($query);
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $obj->bind_param("i", $this->user_id);
        if ($obj->execute()) {
            return $obj->get_result();
        } else {
            return false;
        }
    }
}
