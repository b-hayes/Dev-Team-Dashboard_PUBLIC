<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 26/11/2017
 * Time: 7:51 PM
 */


namespace DTD;

require_once dirname(__FILE__) . "/" . "datatypes.php";


class database extends \mysqli
{
    public $database;

    function __construct() {

        include dirname(__FILE__) . "/" . "db_settings.php"; //added to git ignore file

        parent::__construct($server, $username, $password, $database);

        if ($this->connect_errno > 0) {
            die("<h1> ERROR: The server is not responding try again some other time.</h1>");
        }
        $this->database = $database;
    }

    function insert($class_object){
        $className = get_class($class_object);
        $className = substr($className, strrpos($className, '\\') + 1);
        $columns = "";
        $values = "";
        $numParams = count((array)$class_object);
        $i = 0;
        foreach($class_object as $key => $value) {
            if($key != "id"){
                //cant specify id its autonomic for DTD tables
                if(isset($value)){
                   //only add to the query string if a value was given
                    if($i != 0){
                        //if this isnt the first value put separators in
                        $columns .= ", ";
                        $values .= ", ";
                    }
                    $columns .= "`$key`";
                    $values .= "'$value'";
                    $i++; //count how many values we have to insert so far
                }
            }
        }

        $query = "INSERT into dev_" . $className . "s ($columns) VALUES ($values)";
//        die( "About to perform query : \n $query \n" );
        $this->query($query);
        if(!mysqli_error($this)){
            return "New $className was saved.";
        } else {
            return "Error: " . mysqli_error($this);
        }
    }

    function update($class_object){
        $className = get_class($class_object);
        $className = substr($className, strrpos($className, '\\') + 1);
        $set = "";
        $numParams = count((array)$class_object);
        $i = 0;
        foreach($class_object as $key => $value) {
            $i++;
            if($key != "id"){
                if(isset($value)){
                    if($value=="NULL"){
                        //need to use MYSQL proper NULL setting without quotes
                        $set .= "`$key` = $value";
                    } else {
                        $set .= "`$key` = '$value'";
                    }
                    if($i != $numParams){
                        $set .= ", ";
                    }
                }
            }
        }

        $query = "UPDATE dev_" . $className . "s 
        SET $set
        WHERE `id` = '$class_object->id'";
        echo $query;
        $this->query($query);

        if(!mysqli_error($this)){
            return true;
        } else {
            echo "Error: " . mysqli_error($this);
            return false;
        }
    }

    function delete($class_object){
        $className = get_class($class_object);
        $className = substr($className, strrpos($className, '\\') + 1);

        $query = "DELETE FROM dev_" . $className . "s 
        WHERE `id` = '$class_object->id'";
        $this->query($query);

        if(!mysqli_error($this)){
            return "The $className was deleted.";
        } else {
            return "Error: " . mysqli_error($this);
        }
    }

    function escape_val(&$val){
        $val  = mysqli_real_escape_string($this , $val);
    }

    function escape_array(&$array){
        array_walk_recursive($array, array($this, 'escape_val'));
    }

    function html_chars_val(&$val){
        $val = htmlspecialchars($val);
    }

    function html_chars_array(&$array){
        array_walk_recursive($array, array($this, 'html_chars_val'));
    }

    function clean_val(&$val){
        $this->escape_val($val);
        $this->html_chars_val($val);
    }

    function clean_array(&$array){
        array_walk_recursive($array, array($this, 'clean_val'));
    }
}