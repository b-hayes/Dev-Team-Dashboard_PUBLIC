<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 15/05/2017
 * Time: 8:05 PM
 */
//echo "Post Data:<br>";
//print_r($_POST);
//echo "<br>";
//echo "Request Data:<br>";
//print_r($_REQUEST);
//echo "<br>";
//echo "Get Data:<br>";
//print_r($_GET);
//echo "<br>";
//exit();

require_once dirname(__FILE__) . "/" . "../lib/session_controller.php";
include "../lib/enable_errors.php";
$current = new \DTD\session_controller();
require_once dirname(__FILE__) . "/" . "../lib/flash.php";
if (empty($_POST['name'])){
    die( "No job name received. There might be a server problem. Try again later or ask your admin for help.");
    exit();
}

// Create connection
$db = new \DTD\database();

// prepare and bind
$stmt = $db->prepare("INSERT INTO `dev_jobs`(`name`, `details`, `user`, `CreatedBy`, `DueDate`, `project_id`, `release_id`) VALUES (?, ?, ?,?,?,?,?)");
$stmt->bind_param("sssssii", $jobname, $details, $user, $createdBy, $dueDate, $pid, $rid);

// set parameters and execute
$jobname = htmlspecialchars( $_POST['name'] );
$details = htmlspecialchars( $_POST['details'] );
//Compensate for code snippet feature
$details = str_replace("&lt;PRE&gt;", "<PRE>", $details);
$details = str_replace("&lt;/PRE&gt;", "</PRE>", $details);

$user = $_POST['user'];
$createdBy = $current->user->id;

//MySQL retrieves and displays DATE values in 'YYYY-MM-DD' format.
$dueDate = date("Y-m-d",strtotime($_POST['due-date']) );

$pid = $current->project->id;
$rid = $current->release->id;

if ($stmt->execute()){
    flash("New job created.", "success");
} else {
    $msg = "Error: Failed to add new job.\n" .
        " There might be a server problem. Try again later or ask your admin for help." .
        $stmt->error;
    flash($msg, "danger");
}


$stmt->close();
$db->close();

echo $dueDate;
echo "<BR>" . date("Y-m-d h-i-s",time() );
header("Location: ../index.php");
?>


