<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 3/01/2018
 * Time: 6:52 PM
 */

require_once "../lib/session_controller.php";
include "../lib/enable_errors.php";
require_once "../lib/database.php";
require_once "../lib/flash.php";

if(!isset($_POST["project-id"]) || !isset($_POST["password"])){
    flash("No id or password specified.");
    header("Location: ../index.php");
    exit();
}
$pid = $_POST['project-id'];

$db = new \DTD\database();
$db->clean_array($_POST);

$current = new \DTD\session_controller();
$project = \DTD\project::get_project($pid);

if($project->owner_id != $current->user->id){
    flash("You are not the project owner! Only the project owner can perform this action.
    <br>You should not see any of these actions for project you do not own.
    <br>This has been flaged and logged as suspicouse activity on your account.", "danger");
}

$user = \DTD\user::authorize($current->user->email, $_POST["password"]);
if(!$user){
    flash("Authorisation for action failed.", "warning");
    header("Location: ../index.php");
    exit();
}

$db->query("delete from dev_projects where id='$pid'");
$db->query( "delete from dev_releases where project_id='$pid'");
$db->query("delete from dev_jobs where project_id='$pid'");
$db->close();

flash("Project removed and all releases and jobs it contained.", "success");
header("Location: ../index.php");