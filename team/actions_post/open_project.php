<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 24/11/2017
 * Time: 11:14 AM
 */
if(!isset($_POST["open_project"])){
    die("received no project id");
}
include "../lib/session_controller.php";
$current = new \DTD\session_controller();
$project = \DTD\project::get_project($_POST["open_project"]);
$_SESSION["project"] = serialize($project);
header("Location: ../index.php");

?>