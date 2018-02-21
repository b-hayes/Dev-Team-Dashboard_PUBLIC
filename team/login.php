<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 17/05/2017
 * Time: 4:38 AM
 */
session_start();

if(isset($_POST["email"]) && isset($_POST["password"])){
    include "lib/datatypes.php";
    $user = \DTD\user::authorize($_POST["email"], $_POST["password"]);
    if($user){
        session_start();
        $_SESSION["user"] = serialize($user);
//        print_r($_SESSION);
        header("Location: index.php");
    } else {
        $error = "Invalid email address and/or password";
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
<?php
require_once "lib/flash.php";
flash();
?>
<div class="container-fluid">
    <div class="row">
        <div class="panel panel-default" style="width:300px; margin:0 auto; margin-top: 50px;">

            <div class="panel-heading">

                <h4 class="panel-title" style="text-align: center;"><span class="glyphicon glyphicon-log-in pull-left"></span>Login</h4>
            </div>

            <div class="panel-body">
                <?php
                if(isset($error))
                    echo "<p class='alert-warning'>$error</p>"
                ?>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="email">Enter your email address:</label>
                        <input type="text" class="form-control" name="email">
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" name="password">
                    </div>
                    <!--                <div class="checkbox">-->
                    <!--                    <label><input type="checkbox"> Stay logged in</label>-->
                    <!--                </div>-->
                    <input type="submit" class="btn btn-default btn-block" value="Submit">
                </form>
            </div>
            <div class="panel-footer">
                <a href="register.php" style="text-align: right">No account? Click here to register...</a>
            </div>
        </div>
    </div>
</div>
</body>

