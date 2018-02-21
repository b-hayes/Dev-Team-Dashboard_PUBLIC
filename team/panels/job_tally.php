<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 19/05/2017
 * Time: 11:43 AM
 */

require_once dirname(__FILE__) . "/" . "../lib/database.php";
require_once dirname(__FILE__) . "/" . "../lib/session_controller.php";
$current = new \DTD\session_controller();

//first get list of users/members
$users = \DTD\user::get_all();

if (!$users){
    echo "Error getting user information";
    return;
}
//echo "<p class='well'>Job tally for this release:</p>";
echo "<table class=\"table table-striped\">
    <tr>
        <th>Name</th><th>Jobs Todo</th><th>Jobs Total</th><th>Jobs Completed</th>
    </tr>";

//for each member get the job count..
foreach ($users as $user){
    if($user->has_key_for($current->project) || $user->id == $current->project->owner_id){
        $user_name = $user->name;

        $db = new \DTD\database();

        $result = $db->query("SELECT COUNT(id) AS jobs FROM dev_jobs WHERE user='" . $user->id . "'" .
        "AND release_id='" . $current->release->id . "'");
        $row = $result->fetch_assoc();
        $jobCount = $row['jobs'];

        $result = $db->query("SELECT COUNT(id) AS jobs FROM dev_jobs WHERE user='" . $user->id . "'" .
            "AND release_id='" . $current->release->id . "'" .
        "AND complete='1'");
        $row = $result->fetch_assoc();
        $jobsComleted = $row['jobs'];

        $jobsRemaining = $jobCount - $jobsComleted;

        echo
        "<tr>
        <td>$user_name</td><td>$jobsRemaining</td><td>$jobCount</td><td>$jobsComleted</td>
    </tr>";
    }
}
echo "</table>";
?>

