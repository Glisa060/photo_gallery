<?php
require_once 'config.php';

class MySQLDatabase
{
    private $connection;
    public $last_query;
    private $magic_quotes_active;
    private $real_escape_string_exists;


    function __construct()
    {
        $this->open_connection();
        $this->magic_quotes_active = get_magic_quotes_gpc();
        $this->real_escape_string_exists = function_exists("mysql_real_escape_string");
    }

    public function open_connection()
    {
        $this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
        if(!$this->connection)
        {
            die("Connection broken!" . mysqli_error($this->connection));
        }
        else
            {
                $db_select = mysqli_select_db($this->connection,DB_NAME);
                if(!$db_select)
                {
                    die("Connection failed!" . mysqli_error($this->connection));
                }
            }
    }

    public function close_connection()
    {
        if(isset($connection))
        {
            mysqli_close($this->connection);
            unset($this->connection);
        }
    }

    public function query($sql)
    {
        $this->last_query = $sql;
        $result = mysqli_query($this->connection, $sql);
        $this->confirm_query($result);
        return $result;
    }

    private function confirm_query($result)
    {
        if (!$result)
        {
            $output = "Database query failed: " . mysqli_error($this->connection) . "<br/><br/>";
            $output .= "Last SQL query: " .$this->last_query;
            die($output);
        }
    }

    public function escape_value($value)
    {
        if($this->real_escape_string_exists)
        {
            if($this->magic_quotes_active)
            {
                $value = stripslashes($value);
            }

            else
            {
                if (!$this->magic_quotes_active)
                {
                    $value = addslashes($value);
                }
            }
        }
        return $value;
    }

    public function fetch_array($result_set)
    {
        return mysqli_fetch_array($result_set);
    }

    public function affected_rows()
    {
        return  mysqli_affected_rows($this->connection);
    }

    public function query_success()
    {
        return mysqli_affected_rows($this->connection);
    }
}
$database = new MySQLDatabase();
$db =& $database;
