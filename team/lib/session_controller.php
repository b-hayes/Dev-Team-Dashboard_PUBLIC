<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 30/11/2017
 * Time: 10:36 PM
 */


namespace DTD;

if(!isset($_SESSION)){
    session_start();
}
if(!isset($_SESSION["user"])){
    header("Location: login.php");
    exit();
}

//check if theres a default timezone and set one if needed.
if( ! ini_get('date.timezone') )
{
    /* note MYSQL timezone is set to slave
    SELECT @@global.time_zone, @@session.time_zone;
    However since php had no default timezone set there were differences in auto timestamps

    mysql> SELECT TIMEDIFF(NOW(), UTC_TIMESTAMP);
+--------------------------------+
| TIMEDIFF(NOW(), UTC_TIMESTAMP) |
+--------------------------------+
| 00:00:00                       |
+--------------------------------+

    MYSQL has no offset hence just using GMT in php.
    */
    date_default_timezone_set('GMT');//Australia/Hobart
}

class session_controller
{
    /* @var $user user */
    var $user;
    /* @var $user project */
    var $project;
    /* @var $release release */
    var $release;

    public function __construct()
    {
        require_once dirname(__FILE__) . "/" . "datatypes.php";

        $this->user = unserialize($_SESSION["user"]);
        $this->user = user::get_user($this->user->id);//serialized objects loose their accociated function so have to create it again from the class...
        $this->user->update_active();

        if(isset($_SESSION["project"])){
            $this->project = unserialize($_SESSION["project"]);
            $this->project = project::get_project($this->project->id);
        }

        if(isset($_SESSION["release"])){
            $this->release = unserialize($_SESSION["release"]);
            $this->release = release::get_release($this->release->id);
        }
    }
}