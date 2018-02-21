<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 19/12/2017
 * Time: 4:00 PM
 */
require_once dirname(__FILE__) . "/" . "../lib/flash.php";
require_once dirname(__FILE__) . "/" . "../lib/session_controller.php";
$current = new \DTD\session_controller();
if(isset($_POST["project_id"])){
    $project = \DTD\project::get_project($_POST["project_id"]);
    if(!$project){
        die("that project doesnt exist");
    }
} else {
    die("no project was specified");
}


$users = \DTD\user::get_all();

echo "
<form action='actions_post/set_user_access.php' method='post'>
    <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'>&times;</button>
        <h2>Change who can see this Project</h2>
    </div>
    <div class='modal-body' style='overflow-y: auto'>
        <input type='hidden' name='project_id' value='$project->id'>
    <p>Suggestions for roles:</p>
    <ul>
        <li>Lead Programmer</li>
        <li>Lead Artist</li>
        <li>Marketing Coordinator</li>
        <li>Client Liaison</li>
        <li>Manual Coordinator</li>
    </ul>
    <table class='table'>
        <tr><th>Users allowed</th><th>Role description (optional)</th></tr>";
        foreach ($users as $user){
            $checked = "";
            if($user->has_key_for($project)){
                $checked = "checked";
            }

            //but for now were showing everyone anyway because they might want to add a rol name
                echo "
            <tr>
                <td><input type='checkbox' name='user_ids[]' value='$user->id' $checked>  " . $user->display();
            if($user->id == $project->owner_id) {
                echo "<br>This is the project owner who will always have access however, setting this will give the owner a role.";
            }
               echo " </td>
                <td><input type='text' name='roles[" . $user->id . "]' value='Collaborator'></td>
            </tr>";

        }
    echo "
    </table>
    </div>
    <div class='modal-footer'>
        <button type='submit' class='btn btn-success pull-right'>Save changes</button>
        <button class='btn btn-default pull-right' data-dismiss='modal'>Cancel</button>
    </div>
</form>";
