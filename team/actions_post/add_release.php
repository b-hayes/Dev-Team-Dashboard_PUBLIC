<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 24/11/2017
 * Time: 12:17 PM
 */
include "../lib/session_controller.php";
include "../lib/enable_errors.php";
include "../lib/flash.php";
if (empty($_POST['name'])){
    flash("No release name/number. Release not saved", "warning");
    header("Location: ../index.php");
    exit();
}
// Create connection
require_once "../lib/database.php";
$conn = new DTD\database();
$conn->clean_array($_POST);
// prepare and bind
$stmt = $conn->prepare("INSERT INTO `dev_releases`(`name`, `project_id`) VALUES (?, ?)");
$stmt->bind_param("si", $name, $pid);

// set parameters and execute
$name=$_POST["name"];
$pid = $_POST["pid"];

$rid_created = null; //used later
if ($stmt->execute()){
    $rid_created = $stmt->insert_id;
    echo "<BR>Release was created with rid: $rid_created<BR>";
    flash("Release created.", "success");
} else {
    $message = "Error: Failed to add new release\n" .
        " There might be a server problem. Try again later or ask your admin for help." .
        $stmt->error;
    flash($message, "danger");
    exit();
}

$stmt->close();

//new release added, now to move any old jobs over to the new release...
//All unfinished jobs from previous release of the project will be pushed forward to this new release schedule.
//All jobs for this project that do not have a release number (from the old system) will also be pushed forward.

$sql = "SELECT `id`, `project_id`,`complete` FROM dev_jobs WHERE `project_id`='$pid' AND ( `complete`='0' OR `release_id` IS NULL )";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "<br>Could not find jobs with project id $pid";
}
echo "<br>NOTE:" . $result->num_rows . " records to be updated<br>";
while ($row = $result->fetch_assoc()){
    echo "<br>";
    print_r($row);
}

//do the update...
// prepare and bind
$stmt = $conn->prepare("UPDATE `dev_jobs` SET `release_id`=? WHERE `project_id`='$pid' AND ( `complete`='0' OR `release_id` IS NULL )");
$stmt->bind_param("i", $rid_created);//the var was set earlier

// set parameters and execute
$name=$_POST["name"];
$pid = $_POST["pid"];


if ($stmt->execute()){
    flash("Release created and " . $result->num_rows . " jobs were pushed forward.", "success");
} else {
    $message = "Release Created but failed to fush any jobs forward.<br>" .
        " There might be a server problem. Please contact an administrator and give them the following details:<br>" .
        $stmt->error;
    flash($message, "danger");
}

$stmt->close();

$conn->close();

header("Location: ../index.php");