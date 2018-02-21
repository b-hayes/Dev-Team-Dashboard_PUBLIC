<?php

require_once dirname(__FILE__) . "/" . "lib/session_controller.php";
$current = new \DTD\session_controller();
//require_once dirname(__FILE__) . "/" . "lib/enable_errors.php";

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
<!--                                              NAB BAR                                             -->

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#" currentUser="<?php echo $current->user->name; ?>">
                <span class="glyphicon glyphicon-fire"></span>
                Dev Team Dashboard : <?php echo $current->user->name; ?> </a>
        </div>
        <ul class="nav navbar-nav">
            <?php
            if(!isset($current->project)){
                echo "<li class='active'><a href=''>Projects</a></li>";
            } else if(!isset($current->release)){
                echo "<li class=''><a href='actions_post/close_project.php'>Projects</a></li>";
                echo "<li class='active'><a href=''>Release Schedule [ " . $current->project->name . " ]</a></li>";
            } else {
                echo "<li class=''><a href='actions_post/close_project.php'>Projects</a></li>";
                echo "<li class=''><a href='actions_post/close_release.php'>Release Schedule</a></li>";
                echo "<li class='active'><a href=''>Jobs for [ " . $current->project->name . " > Release " . $current->release->name . " ]</a></li>";
            }
            ?>
            
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Log Out</a></li>
        </ul>
    </div>
</nav>


<!--                                              DASHBOARD                                           -->
<div class="container-fluid">
    <div class="row">

        <div id="main-content" class="col-md-8">
            <?php
            if(!isset($current->project)){
                include "panels/projects_cards.php";
            }
            else if(!isset($current->release)){
//                include "test.php";
                include "panels/releases_cards.php";
            }
            else {
                include ("panels/jobs_acordian.php");
            }

            ?>
        </div>

        <div id="side-content" class="col-md-4">
            <?php include "panels/user_stats.php"; ?>
        </div>

    </div>
</div>

</body>
</html>