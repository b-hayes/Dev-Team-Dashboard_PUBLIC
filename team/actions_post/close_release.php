<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 26/11/2017
 * Time: 7:38 PM
 */

if(!isset($_SESSION)){
    session_start();
}
unset($_SESSION["release"]);
header("Location: ../index.php");