<?php
require_once 'initialize.php';
require_once LIB_PATH.DS.'database.php';
class User extends DatabaseObject
{
    protected static $table_name = 'users';
    public $id;
    public $username;
    public $password;
    public $first_name;
    public $last_name;

    public function full_name()
    {
        if(isset($this->first_name) && isset($this->last_name))
        {
            return $this->first_name  . " " . $this->last_name;
        }
        else
        {
            return " ";
        }
    }

    public static function authenticate($username='', $password='')
    {
        global $database;
        $username = $database->escape_value($username);
        $password = $database->escape_value($password);

        $sql = "SELECT * FROM users WHERE username = '{$username}' AND password = '{$password}' LIMIT 1";
        $result_array = self::find_by_sql($sql);
        return !empty($result_array) ? array_shift($result_array) : false;
    }

    public function save()
    {
        return isset($this->id) ? $this->update() : $this->create();
    }

    public function create()
    {
        global $database;
        $sql = "INSERT INTO users (";
        $sql .="username, password, first_name, last_name";
        $sql .=") VALUES ('";
        $sql .= $database->escape_value($this->username) ."', '";
        $sql .= $database->escape_value($this->password) ."', '";
        $sql .= $database->escape_value($this->first_name) ."' , '";
        $sql .= $database->escape_value($this->last_name) ."')";
        if ($database->query($sql))
        {
            $this->id = $database->query_success();
            return true;
        }
        else
        {
            return false;
        }
    }

    public function update()
    {
        global $database;
        $sql = "UPDATE users SET ";
        $sql .= "username='" . $database->escape_value($this->username) . "', ";
        $sql .= "password='" . $database->escape_value($this->password) . "', ";
        $sql .= "first_name='" . $database->escape_value($this->first_name) . "', ";
        $sql .= "last_name='" . $database->escape_value($this->last_name) . "' ";
        $sql .= "WHERE id='" . $database->escape_value($this->id) . "' ";
        $database->query($sql);
        return ($database->affected_rows() == 1) ? true : false;
    }

    public function delete()
    {
        global $database;
        $sql = "DELETE FROM users ";
        $sql .= "WHERE id= " . $database->escape_value($this->id);
        $sql .= " LIMIT 1";
        $database->query($sql);
        return ($database->affected_rows() == 1) ? true : false;
    }
}
