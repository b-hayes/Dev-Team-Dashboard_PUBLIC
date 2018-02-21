<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 17/05/2017
 * Time: 4:49 AM
 */

require_once dirname(__FILE__) . "/" . "../lib/session_controller.php";
$current = new \DTD\session_controller();
$users = \DTD\user::get_all();

echo "<option value=\"Unclaimed\">Unclaimed (anyone can take this job by editing it)</option>";
echo "<option value=\"Everyone\">Everyone (all team members need to help with this job)</option>";
foreach ($users as $user){
    $userId = $user->id;
    $userName = $user->display();
    if($user->id == $current->project->owner_id){
        $userRole = "Project Owner";
    }
    $key = $user->key_for($current->project);
    if($key){
        $userRole = $key->role; //overwrites project owner if owner was also given a role via a key
    }
    //if the user has a role they should be in the list for assigning jobs
    if(isset($userRole)){
        echo "<option value='". $userId ."'>". $userName . " : " . $userRole . "</option>";
        unset($userRole);
    }
}