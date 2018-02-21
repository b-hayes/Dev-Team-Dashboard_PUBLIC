<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 5/01/2018
 * Time: 3:24 PM
 */
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

<body style="text-align: center">
    <h1>Dev Team Dashboard</h1>
    <p>This site was originally a quick hack job created while at university to quickly get some very simple tool for
    tracking and assigning jobs to members of a university project team. I have recently updated the dashboard to allow
    for user registration, multiple projects with multiple release versions and the ability to invite users to projects
    and assign them roles.</p>
    <p>Now the Dashboard is open to the public. Partly just to show people what I can quickly create in a short span of time
    and also to see if this dashboard would actually be usefull to other people.</p>
    <h2>Feedback / Suggestions / Bug Reports</h2>
    <p>If you find any problems with my dashboard or just want to tell me that its great please let me know :P</p>
    <div align="centre" style="text-align: center; max-width: 400px">
        <form method="post">
            <label for="name">Your name (optional)</label>
            <input type="text" name="name" class="form-control">
            <label for="email">Your email address so I can ask you questions (optional)</label>
            <input type="text" name="email" class="form-control">
            <label for="feedback">Your feedback (required)</label>
            <textarea name="feedback" cols="100" rows="20" class="form-control"></textarea>
            <button type="submit" class="btn btn-default">Submit Feedback</button>
        </form>
    </div>

</body>
