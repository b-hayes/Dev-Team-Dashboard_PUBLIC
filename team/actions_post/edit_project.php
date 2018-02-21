<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 3/01/2018
 * Time: 7:35 PM
 */
require_once "../lib/session_controller.php";
require_once "../lib/enable_errors.php";
require_once "../lib/database.php";
require_once "../lib/flash.php";

if(!isset($_POST['id'])){
    flash("No id received!", "danger");
    header("Location: ../index.php");
    exit();
}

$db = new \DTD\database();
$db->clean_array($_POST);
$project = \DTD\project::get_project($_POST['id']);

if(!$project){
    flash("That project doesnt exist!", "danger");
    header("Location: ../index.php");
    exit();
}

$project->name = $_POST['name'];
$project->description = $_POST['description'];

if($db->update($project)){
    flash("Project details changed.", "success");
} else {
    flash("Failed to update project details! Contact website administrator!", "danger");
}

$db->close();
header("Location: ../index.php");