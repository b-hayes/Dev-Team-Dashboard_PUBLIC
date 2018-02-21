<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 15/12/2017
 * Time: 11:03 AM
 */

if(!isset($_SESSION)){ session_start(); }
include "lib/flash.php";

if(isset($_POST["email"])){

    require_once "lib/datatypes.php";
    $db =  new \DTD\database();
    $db->clean_array($_POST);
    $result = $db->query("SELECT * FROM dev_users WHERE `email` = '" . $_POST["email"] . "'");
    if($result && $result->num_rows > 0){
        $error = "That account already exists did you <a href=''>forget your password?</a>";
        flash($error, "warning");
    } else {
        $user = new \DTD\user();
        $user->email = $_POST["email"];
        $user->name = $_POST["name"];
        $user->password = md5($_POST["new_password"]);
        $result = $db->insert($user);
        flash($result);
        header("Location: index.php");
        exit();
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dev Team Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="css/overrides.css">
</head>
<body>
<?php flash(); ?>
<div class="container-fluid">
    <div class="row">
        <div class="panel panel-default" style="max-width: 600px; margin:0 auto; margin-top: 50px;">

            <div class="panel-heading">

                <h4 class="panel-title" style="text-align: center;"><span class="glyphicon glyphicon-import pull-left"></span>
                Create new account</h4>
            </div>
            <div class="alert alert-info">
                <h5>DISCLAIMER:</h5>
                <p>            This site is not secure. I have put it up for people to play with
                    but do not store sensitive information.<br>
                    By creating an account you agree not to hold the site or its owner responsible for any consequences of it's use.
                </p><br>
            <input type="checkbox" value="agree" id="agree"> Yes I understand and agree.
            </div>
            <div class="panel-body">

                <form action="" method="post">
                    <div class="form-group">
                        <label for="name">Enter your name:</label>
                        <br>So your team know who you are and can send you jobs.
                        <input type="text" class="form-control" name="name" id="name">
                    </div>
                    <div class="form-group">
                        <label for="email">Enter your email address:</label>
                        <br>Used to login and reset your password.
                        <input type="text" class="form-control" name="email" id="email">
                    </div>
                    <div class="form-group">
                        <label for="password">New Password:</label>
                        <input type="password" class="form-control pass_confirm" name="new_password" id="p1">
                    </div>
                    <div class="form-group">
                        <label for="password">Confirm New Password:</label>
                        <input type="password" class="form-control pass_confirm" name="confirm_new_password" id="p2">
                    </div>
                    <input type="submit" class="btn btn-default btn-block" value="Enter your details" disabled id="submit">
                </form>
            </div>
        </div>
    </div>
</div>
</body>


<script>
    $(function () {
        function validate() {
            var p1 = $("#p1").val();
            var p2 = $("#p2").val();
            var agree = $("#agree").prop('checked');
            var emial = $("#email").val();
            var name = $("#name").val();

            // alert("key pressed");
            if(p1==p2 && p2!=''){
                $("#submit").prop('disabled', false);
                $("#submit").val('Submit');
            }

            if (agree == false) {
                $("#submit").prop('disabled', true);
                $("#submit").val('You must agree to the disclaimer above...');
            }

            if(p1.length < 8){
                $("#submit").prop('disabled', true);
                $("#submit").val('Passwords must be at least 8 characters long');
            } else if(p1!=p2) {
                $("#submit").prop('disabled', true);
                $("#submit").val('Passwords must match');
            }

            if (emial.indexOf('@') == -1)
            {
                $("#submit").prop('disabled', true);
                $("#submit").val('Enter a valid email address. I wont spam you I promise :)');
            }

            if(name==""){
                $("#submit").prop('disabled', true);
                $("#submit").val('Enter a name.');
            }
        }
        $("input").keyup(validate);
        $("#agree").click(validate);
    });
</script>
