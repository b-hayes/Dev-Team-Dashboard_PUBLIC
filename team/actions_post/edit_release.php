<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 3/01/2018
 * Time: 1:40 AM
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
$rName = $_POST['name'];
$release = \DTD\release::get_release($rid);
if(!$release){
    echo "That release doesnt exist.";
    exit();
}
$db->query("update dev_releases SET name='$rName' where id='$rid'");
flash("Release name changed to " . $rName, "success");
$db->close();
header("Location: ../index.php");
exit();
