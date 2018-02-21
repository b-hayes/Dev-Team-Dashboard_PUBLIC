<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 24/11/2017
 * Time: 3:29 AM
 */
require_once "../lib/session_controller.php";
$current = new \DTD\session_controller();
include "../lib/flash.php";
if (empty($_POST["name"])){
     flash("No project name. Project not saved", "warning");
     header("Location: ../index.php");
     exit();
}

$db = new \DTD\database();
$db->clean_array($_POST);
$project = new \DTD\project();
$project->name = $_POST["name"];
$project->description = $_POST["description"];
$project->owner_id = $current->user->id;
$response = $db->insert($project);
flash($response);
header("Location: ../index.php");