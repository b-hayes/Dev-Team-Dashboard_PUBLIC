<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 19/12/2017
 * Time: 4:00 PM
 */
require_once dirname(__FILE__) . "/" . "../lib/flash.php";
require_once dirname(__FILE__) . "/" . "../lib/session_controller.php";
$current = new \DTD\session_controller();
if(isset($_POST["project_id"])){
    $project = \DTD\project::get_project($_POST["project_id"]);
    if(!$project){
        die("that project doesnt exist");
    }
} else {
    die("no project was specified");
}

$db = new \DTD\database();
$db->query("DELETE FROM `dev_keys` WHERE `project_id`=$project->id ORDER BY `user_id`");

if(isset($_POST["user_ids"])){
    foreach ($_POST["user_ids"] as $user_id){
        $key = new \DTD\key();
        $key->project_id = $project->id;
        $key->user_id = $user_id;
        $key->role = $_POST["roles"][$user_id];
        if($key->role == "god"){
            //there can be only one god
            $key->role = "god.";
        }
        $db->insert($key) . "<br>";
    }
    flash(count($_POST["user_ids"]) . " users total have roles in the  project: '$project->name'.");
} else {
    flash("All user removed from project: $project->name");
}

header("Location: ../index.php");