<?php
class Student
{
    //variable decleration
    public $name;
    public $email;
    public $mobile;
    public $id;

    private $conn;
    private $table_name;

    public function __construct($db)
    {
        $this->conn = $db;
        $this->table_name = "tbl_students";
    }

    public function create_data()
    {
        //sql query to insert data
        $query = "INSERT INTO " . $this->table_name . " SET name = ?, email = ?, mobile=?";

        //prepare the sql query
        $obj = $this->conn->prepare($query);

        //sanitize inout variables => removes the extra chars like special symbols 
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->mobile = htmlspecialchars(strip_tags($this->mobile));

        //binding parameters with prepare statement   
        $obj->bind_param("sss", $this->name, $this->email, $this->mobile);

        //execute query
        if ($obj->execute()) {
            return true;
        } else return false;
    }

    //read all data
    public function get_all_data()
    {
        $sql_query = "SELECT * FROM " . $this->table_name;
        $std_obj = $this->conn->prepare($sql_query);
        //execute query

        $std_obj->execute();
        return $std_obj->get_result();
    }
    //read single student data
    public function get_single_student()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id=?";
        $obj = $this->conn->prepare($query);

        $obj->bind_param("i", $this->id);
        $obj->execute();
        $data = $obj->get_result();
        return $data->fetch_assoc();
    }
    // update student data
    public function update_student()
    {
        $query = "UPDATE " . $this->table_name . " SET name = ? , email = ? , mobile = ? WHERE id = ?";
        $obj = $this->conn->prepare($query);
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->mobile = htmlspecialchars(strip_tags($this->mobile));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $obj->bind_param("sssi", $this->name, $this->email, $this->mobile, $this->id);

        //execute query
        if ($obj->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function delete_student()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=?";
        $obj = $this->conn->prepare($query);
        $this->id = htmlspecialchars((strip_tags($this->id)));
        $obj->bind_param("i", $this->id);

        if ($obj->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
