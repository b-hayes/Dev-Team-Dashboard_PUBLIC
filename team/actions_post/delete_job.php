<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 3/01/2018
 * Time: 3:45 PM
 */

include "../lib/enable_errors.php";
require_once "../lib/flash.php";
require_once "../lib/database.php";
$db= new \DTD\database();
$db->clean_array($_POST);
$stmt = $db->prepare("delete from dev_jobs where id=?");
$stmt->bind_param("i", $_POST['job-id']);

if($stmt->execute()){
    flash("Job deleted.", "success");
} else {
    $msg = "Error: Failed.<br>" .
        " There might be a server problem. Give the following info to you admin:<br>" .
        $stmt->error;
    flash($msg, "danger");
}
$stmt->close();
$db->close();

header("Location: ../index.php");
exit();