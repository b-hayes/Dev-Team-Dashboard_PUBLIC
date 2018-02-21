<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 17/05/2017
 * Time: 6:16 AM
 */
if(!isset($_SESSION))      {          session_start();      }
session_destroy();
header("Location: ./login.php");