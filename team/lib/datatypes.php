<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 28/11/2017
 * Time: 12:43 PM
 */

namespace DTD;

require_once dirname(__FILE__) . "/" . "database.php";

function br($text = ""){
    echo $text . "\n";
}
//this function was only used once to generate starter code for the class objects.
function generate_class_code_from_database(){
    $db = new database();
    $tables = $db->query("show tables");
    br("COPY THE FOLLOWING CODE TO THE PHP FILE<pre>");
    br("namespace DTD;");
    br();

    while ($table = $tables->fetch_assoc()){
        $tableName = $table["Tables_in_" . $db->database];
        //because the table start with dev_ and have an s on the end. eg. dev_users will become class called user.
        $className = substr($tableName, 4, -1);
        br("class " . $className);
        br("{");
        //variables
        $colums = $db->query("describe " . $tableName);
        while ($column = $colums->fetch_assoc()){
            $varName = $column["Field"];
            br("    var $". $varName. ";");
            if(isset($column["Key"])){
                if($column["Key"] == "PRI"){
                    //this var is the primary key so use for constructor to get data
                    $primaryKey = $varName;
                }
            }
        }

        if(isset($primaryKey)){
            br( "
    static function get_$className($$primaryKey) {
        if (isset($$primaryKey)){
            \$db = new database();
            /* @var \$object $className */ //so auto completions works in your ide :)
            \$object = \$db->query(\"SELECT * FROM `$tableName` WHERE `$primaryKey`='$$primaryKey'\")->fetch_object('\DTD\\$className');
            \$db->close();
            return \$object;
        }
    }");
        }

        br("}");
        br();
    }

    br("</pre>");
}

class job
{
    var $id;
    var $project_id;
    var $release_id;
    var $name;
    var $details;
    var $complete;
    var $user;
    var $CompletedBy;
    var $DueDate;
    var $CompletedOn;
    var $CreatedBy;
    var $CreatedOn;

    static function get_job($id) {
        if (isset($id)){
            $db = new database();
            /* @var $object job */ //so auto completions works in your ide :)
            $object = $db->query("SELECT * FROM `dev_jobs` WHERE `id`='$id'")->fetch_object('\DTD\job');
            return $object;
        }
    }
}

class project
{
    var $id;
    var $name;
    var $description;
    var $created;
    var $owner_id;

    static function get_project($id) {
        if (isset($id)){
            $db = new database();
            /* @var $object project */ //so auto completions works in your ide :)
            $object = $db->query("SELECT * FROM `dev_projects` WHERE `id`='$id'")->fetch_object('\DTD\project');
            $db->close();
            return $object;
        }
    }

    static function get_all(){
        $db = new database();
        $result = $db->query("SELECT * FROM `dev_projects`");
        if(!$result){
            $db->close();
            return false;
        }
        /* @var $objects project[] */
        $objects = array();
        while ($item = $result->fetch_object('\DTD\project')){
            array_push($objects, $item);
        }
        $db->close();
        return $objects;
    }

    function releases(){
        $db = new database();
        $result = $db->query("SELECT * FROM `dev_releases` WHERE `project_id`='" . $this->id . "'");
        if(!$result){
            $db->close();
            return false;
        }
        /* @var $objects release[] */
        $objects = array();
        while ($item = $result->fetch_object('\DTD\release')){
            array_push($objects, $item);
        }
        $db->close();
        return $objects;
    }
}

class release
{
    var $id;
    var $name;
    var $project_id;
    var $opened;
    var $closed;

    static function get_release($id) {
        if (isset($id)){
            $db = new database();
            /* @var $object release */ //so auto completions works in your ide :)
            $object = $db->query("SELECT * FROM `dev_releases` WHERE `id`='$id'")->fetch_object('\DTD\release');
            $db->close();
            return $object;
        }
    }

    static function get_all(){
        $db = new database();
        $result = $db->query("SELECT * FROM `dev_releases`");
        if(!$result){
            $db->close();
            return false;
        }
        /* @var $objects release[] */
        $objects = array();
        while ($item = $result->fetch_object('\DTD\release')){
            array_push($objects, $item);
        }
        $db->close();
        return $objects;
    }

    function job_count(){
        if(isset($this->id)){
            $db = new database();
            $result = $db->query("SELECT COUNT(*) AS jobCount FROM `dev_jobs` WHERE `release_id`='" . $this->id . "'");
            $row = $result->fetch_assoc();
            $db->close();
            return $row["jobCount"];
        }
        return 0;
    }

    function jobs(){
        $db = new database();
        $result = $db->query("SELECT * FROM dev_jobs WHERE `release_id`='" . $this->id . "' ORDER BY `complete` ASC, `DueDate` ASC");
        if(!$result){
            $db->close();
            return false;
        }
        /* @var $objects job[] */
        $objects = array();
        while ($item = $result->fetch_object('\DTD\job')){
            array_push($objects, $item);
        }
        $db->close();
        return $objects;
    }
}

class stint
{
    var $id;
    var $start;
    var $finish;
    var $note;
    var $user_id;
    var $release_id;

    static function get_stint($id) {
        if (isset($id)){
            $db = new database();
            /* @var $object stint */ //so auto completions works in your ide :)
            $object = $db->query("SELECT * FROM `dev_stints` WHERE `id`='$id'")->fetch_object('\DTD\stint');
            return $object;
        }
    }
}

class user
{
    var $id;
    var $name;
    var $email;
    var $password;
    var $last_active;

    static function get_user($id) {
        if (isset($id)){
            $db = new database();
            /* @var $object user */ //so auto completions works in your ide :)
            $object = $db->query("SELECT * FROM `dev_users` WHERE `id`='$id'")->fetch_object('\DTD\user');
            $object->password = ""; //dont keep the pasword
            return $object;
        }
    }

    //used for login
    static function authorize($email, $password){
        $db = new database();
        $result = $db->query("SELECT * FROM `dev_users` WHERE `email`='$email'");
        if($result){
            /* @var $object user */
            $object = $result->fetch_object();
            if(md5($password) == $object->password){
                $object->password = "";
                return $object;
            }
        }
        return false;
    }

    static function get_all(){
        $db = new database();
        $result = $db->query("SELECT * FROM `dev_users`");
        if(!$result){
            return false;
        }
        /* @var $objects user[] */
        $objects = array();
        while ($item = $result->fetch_object('\DTD\user')){
            array_push($objects, $item);
        }
        return $objects;
    }

    //to be modified later to include a users custom icon etc...
    function display(){
        return "<span class='glyphicon glyphicon-user'></span> $this->name";
    }

    function update_active(){
        $db = new database();
        $db->query("UPDATE dev_users SET last_active = NOW() WHERE id = " . $this->id);
        $db->close();
    }

    function is_god(){
        $db = new database();
        $result = $db->query("SELECT * FROM `dev_keys` WHERE `user_id`='$this->id' AND `role`='god'");
        if($result && $result->num_rows > 0){
            $db->close();
            return true;
        }
        $db->close();
        return false;
    }

    function jobCount(){
        $db = new database();
        $result = $db->query("SELECT COUNT(id) AS jobs FROM dev_jobs WHERE user='" . $this->id . "'");
        $row = $result->fetch_assoc();
        $db->close();
        return $row['jobs'];
    }


    function jobCompleteCount(){
        $db = new database();
        $result = $db->query("SELECT COUNT(id) AS jobs FROM dev_jobs WHERE user='" . $this->id . "' and complete=1");
        $row = $result->fetch_assoc();
        $db->close();
        return $row['jobs'];
    }

    function projectCount(){
        $projects = project::get_all();
        $count = 0;
        foreach ($projects as $project){
            if($this->is_part_of($project)){
                $count++;
            }
        }
        return $count;
    }

    /**
     * @param $class_object
     * @return bool
     * DO NOT USE THIS IF ONLY 1 or 2 CONDITION(s) NEEDS CHECKING!
     * Check for 3 conditions that return true
     * 1.   The user is god
     * 2.   The object has an owner_id that matches the this->id
     * 3.   User has a key to access the object
     * If no condition is met false is returned.
     */
    function can_access($class_object)
    {
        if ($this->is_god()) {
            //god has access to everything
            return true;
        }

        if($this->owns($class_object)){
            return true;
        };

        if($this->has_key_for($class_object)){
            return true;
        }

        return false;
    }

    function is_part_of($class_object)
    {
        if($this->owns($class_object)){
            return true;
        };

        if($this->has_key_for($class_object)){
            return true;
        }

        return false;
    }

    function owns($class_object){
        if(property_exists($class_object, "owner_id")){
            //owners have access if thats possible for the object
            if($class_object->owner_id == $this->id){
                return true;
            }
        }
        return false;
    }


    /**
     * @param $class_object
     * @return bool
     * This currently only works for project but is written to be generalized in case keys are needed for other classes later on.
     * For extended use in future the keys table would have to contain a classname_id feild for the class object you want to check
     * the user has a key for.
     */
    function has_key_for($class_object){
        $className = get_class($class_object);
        $className = substr($className, strrpos($className, '\\') + 1);
        $columnToCheck = $className . "_id";
        $valueToCheck = $class_object->id;
        $db = new database();
        $result = $db->query("SELECT * FROM `dev_keys` WHERE `$columnToCheck`='$valueToCheck' AND `user_id`='$this->id'");
        if($result && $result->num_rows > 0){
            $db->close();
            return true;
        }
        $db->close();
        return false;
    }

    //same as has_key_for but returns the actual key rather than a bool
    function key_for($class_object){
        $className = get_class($class_object);
        $className = substr($className, strrpos($className, '\\') + 1);
        $columnToCheck = $className . "_id";
        $valueToCheck = $class_object->id;
        $db = new database();
        $result = $db->query("SELECT * FROM `dev_keys` WHERE `$columnToCheck`='$valueToCheck' AND `user_id`='$this->id'");
        if($result && $result->num_rows > 0){
            /* @var $key key */
            $key = $result->fetch_object("\DTD\key");
            $db->close();
            return $key;
        }
        $db->close();
        return false;
    }
}

class key
{
    var $id;
    var $user_id;
    var $role;
    var $project_id;
    var $release_id;

    static function get_key($id) {
        if (isset($id)){
            $db = new database();
            /* @var $object key */ //so auto completions works in your ide :)
            $object = $db->query("SELECT * FROM `dev_keys` WHERE `id`='$id'")->fetch_object('\DTD\key');
            $db->close();
            return $object;
        }
    }
}
