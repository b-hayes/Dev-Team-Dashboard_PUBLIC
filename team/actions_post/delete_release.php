<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 2/01/2018
 * Time: 10:10 PM
 */

include "../lib/enable_errors.php";

if(!isset($_POST['rid'])){
    echo "No release id received";
    exit();
}
require_once "../lib/database.php";
require_once "../lib/flash.php";
$db = new \DTD\database();
$db->clean_array($_POST);
$rid = $_POST['rid'];

$release = \DTD\release::get_release($rid);
if(!$release){
    echo "That release doesnt exist.";
    exit();
}

$db->query("delete from dev_releases where id='$rid'");

if(isset($_POST['deleteAllJobs'])){
    $db->query("delete from dev_jobs where release_id='$rid'");
    flash("Release was deleted along with " . $release->job_count() . " jobs that it contained.");
} else {
    $db->query("UPDATE dev_jobs SET release_id = NULL WHERE  release_id = '$rid'");
    flash("Release was deleted and it's " . $release->job_count() . " jobs were archived. These archived jobs will return if a new release is created.");
}
$db->close();
header("Location: ../index.php");
exit();
