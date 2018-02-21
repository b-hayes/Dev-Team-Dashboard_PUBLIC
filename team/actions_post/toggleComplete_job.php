<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 3/01/2018
 * Time: 5:25 PM
 */

require_once dirname(__FILE__) . "/" . "../lib/session_controller.php";
require_once "../lib/enable_errors.php";
require_once dirname(__FILE__) . "/" . "../lib/flash.php";
if (!isset($_POST['complete-job-id'])){
    echo "No job id received";
    exit();
}
require_once dirname(__FILE__) . "/" . "../lib/session_controller.php";
require_once dirname(__FILE__) . "/" . "../lib/flash.php";
$current = new \DTD\session_controller();
$db = new \DTD\database();
$db->clean_array($_POST);
$job = \DTD\job::get_job($_POST['complete-job-id']);

if($job->complete){
    //job was marked as complete and now details should be removed
    $job->complete = 0;
    $job->CompletedBy = "NULL";
    $job->CompletedOn = "NULL";
} else {
    //job wasnt completed before and now should be marked as complete with details
    $job->complete = 1;
    $job->CompletedBy = $current->user->id;
    $job->CompletedOn = date("Y-m-d");
}
if($db->update($job)){
    flash("Job status updated.", "success");
} else {
    flash("FATAL ERROR: Job status failed to update! Contact the websites administrator if this happens again.", "danger");
}
$db->close();
header("Location: ../index.php");
exit();