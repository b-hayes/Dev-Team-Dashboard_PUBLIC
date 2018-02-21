<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 17/05/2017
 * Time: 12:59 AM
 */
if (empty($_POST[id])){
    echo "No project id received";
    exit();
}
require_once dirname(__FILE__) . "/" . "../lib/session_controller.php";
$current = new \DTD\session_controller();
$conn = new \DTD\database();
$sql = "SELECT * FROM `dev_projects` WHERE id=$_POST[id]";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    //we only want 1st result..
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo "Failed to get project";
}
$conn->close();