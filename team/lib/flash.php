<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 24/11/2017
 * Time: 3:42 AM
 */

function flash($message = null, $type="info"){
    if(!isset($_SESSION))      {          session_start();      }
    if(isset($message)){
        $_SESSION["flash"]["message"] = $message;
        $_SESSION["flash"]["type"] = $type;
    } else {
        if(isset($_SESSION["flash"])){
            $message = $_SESSION["flash"]["message"];
            $type = $_SESSION["flash"]["type"];
            echo "<div class='alert alert-$type fade in alert-dismissable' style='text-align: center'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <span class='glyphicon glyphicon-comment'></span>
                    <strong style='text-align: center'> $message</strong>
                </div>";

            unset($_SESSION["flash"]);
        }
    }
}