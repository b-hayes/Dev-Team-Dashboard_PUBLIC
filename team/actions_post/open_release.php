<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 24/11/2017
 * Time: 2:49 PM
 */

if(!isset($_POST["open_release"])){
    die("received no release id");
}
include "../lib/session_controller.php";
$current = new \DTD\session_controller();
$release = \DTD\release::get_release($_POST["open_release"]);
$_SESSION["release"] = serialize($release);
header("Location: ../index.php");

?>