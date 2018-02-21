<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 26/11/2017
 * Time: 7:38 PM
 */

require_once dirname(__FILE__) . "/" . "../lib/session_controller.php";
$current=  new \DTD\session_controller();
unset($_SESSION["project"]);
//just in case
unset($_SESSION['release']);
header("Location: ../index.php");