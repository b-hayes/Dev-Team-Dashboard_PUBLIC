<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 3/01/2018
 * Time: 8:33 PM
 */

require_once dirname(__FILE__) . "/" . "../lib/session_controller.php";
$current = new \DTD\session_controller();

?>

<div class="panel">
    <div class="panel-body">
        <?php
        if($current->user->is_god()){

            //some things only for me :)

            echo "Current user list:<br>";
            $allUsers = \DTD\user::get_all();
            foreach ($allUsers as $user){
                echo $user->display() . "<br>";
            }

            $db = new \DTD\database();
            $result  = $db->query("SELECT COUNT(id) AS count FROM dev_projects")->fetch_assoc();
            $count = $result['count'];
            echo "There are $count projects in the system.<br>";
        }
        $jobCount = $current->user->jobCount();
        $jobsComleted = $current->user->jobCompleteCount();
        $jobsRemaining = $jobCount - $jobsComleted;
        //prevent divide by zero
        $percentage = ($jobCount>0)? (100/$jobCount) * $jobsComleted : 0;
        $projectCount = $current->user->projectCount();
        ?>
        <p>You are involved in <?php echo $projectCount; ?> projects.</p>
        <p>
            You have <?php echo $jobCount; ?> Jobs
            and have compleated <?php echo $jobsComleted; ?> of them.
        </p>
        <p>
            You have <?php echo $jobsRemaining; ?> jobs remaining. (across all projects)
        </p>
        <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $jobsComleted; ?>"
             aria-valuemin="0" aria-valuemax="<?php echo $jobCount; ?>" style="width:<?php echo $percentage ?>%">
            <?php echo $percentage ?>%
        </div>
    </div>
</div>
