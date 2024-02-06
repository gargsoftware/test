<?php
class DbConnectivity1
{
    static $con;
    function __construct()
    {
        $this->Connnection();
    }
       
    // database connection function....................
    function Connnection()
    {
        $HostName = 'localhost';
        $UserName = 'root';
        $Password = '';
        $DataBase = 'input_form';
        if (self::$con == null) {
            self::$con = new mysqli($HostName, $UserName, $Password, $DataBase);
            if (self::$con->connect_error) {
                return false;
            }
        }
        return true;
    }

    function delete($sql)
    {
        if (mysqli_query(self::$con, $sql))
            return true;
        else
            return false;
    }

    function QueryExecute($sql)
    {  
        if (self::$con != null) {
            mysqli_query(self::$con, $sql);
            if (self::$con->affected_rows > 0 )
                return true;
            else
                return false;
        }
    }

    function Execute($sql)
    {  
        if (self::$con != null) {
           $whiledata = mysqli_query(self::$con, $sql);
           return $whiledata;
        }
    }

    function Update($sql)
    {
        if (mysqli_query(self::$con, $sql))
            return true;
        else
            return false;
    }

    function CheckAlreadyExists($sql)
    {
      $result = mysqli_query(self::$con, $sql);
      $num_rows = mysqli_num_rows($result);
            if ($num_rows)
                return true;
            else
                return false;
    }

    function RetrieveData($sql)
    {
        $result = mysqli_query(self::$con, $sql);
        $Data_array = [];
        while ($row = $result->fetch_assoc()) {
            array_push($Data_array, $row);
        }
        return $Data_array;
    }

    function total_rows($sql)
    {
      // print_r($sql);die;
        $result = mysqli_query(self::$con, $sql);
        $num_rows = mysqli_num_rows($result);
        return $num_rows;
    }

    function error()
    {
        return mysqli_error(self::$con);
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }

  }
