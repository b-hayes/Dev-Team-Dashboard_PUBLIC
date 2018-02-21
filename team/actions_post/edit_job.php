<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 3/01/2018
 * Time: 5:59 PM
 */

require_once "../lib/session_controller.php";
require_once "../lib/enable_errors.php";
require_once "../lib/database.php";
require_once "../lib/flash.php";

if(!isset($_POST['job-id'])){
    die("No job ID received");
}
$db = new \DTD\database();
$db->clean_array($_POST);
$job = \DTD\job::get_job($_POST['job-id']);
$job->name = $_POST['name'];
$job->details = $_POST['details'];
$job->user = $_POST['user'];
$job->DueDate = date("Y-m-d",strtotime($_POST['due-date']) );

if($db->update($job)){
    flash("Job details updated.", "success");
} else {
    flash("FATAL ERROR: Failed to save job! Contact the websites administrator if this happens again.", "danger");
}
$db->close();
header("Location: ../index.php");
exit();