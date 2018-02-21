<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 15/05/2017
 * Time: 11:35 PM
 */
require_once dirname(__FILE__) . "/" . "../lib/session_controller.php";
$current = new \DTD\session_controller();
$jobs = $current->release->jobs();
?>
<div class="panel-group" id="accordionJobList">
<?php

if ($jobs) {
    // output data of each row
    $count=1;

    foreach ($jobs as $job){
        //job styles are based on user names so if the jobs user is a number it should use this as a user id and get the current name
        if(is_numeric($job->user)){
            $job->user = \DTD\user::get_user($job->user)->name;
        }
        $icon = "glyphicon-unchecked";
        $panel_class = "primary";
        $user_class = $panel_class;
        if($job->user == "Everyone"){
            $panel_class = "info";
            $user_class = $panel_class;
        }
        if($job->user == $current->user->name){
            $panel_class = "success";
            $user_class = $panel_class;
        }
        if($job->user == "Unclaimed"){
            $panel_class = "warning";
            $user_class = $panel_class;
        }
        
        //for display we dont want to show dates that wernt set..
        $DueDate = "No date specified";
        if ($job->DueDate!='0000-00-00' && $job->DueDate!='1970-01-01'){
            $DueDate = date('d F Y', strtotime($job->DueDate));
        }
        $CreatedOn = "before dates were tracked";
        if ($job->CreatedOn!='0000-00-00 00:00:00'){ //more 0's because its a timestamp
            $CreatedOn = date('d F Y', strtotime($job->CreatedOn));
        }

        //check if its overdue!
        //but if the date is 0000-00-00 then strtotime returns nothing ''
        $date = strtotime($job->DueDate);
        $now = strtotime(date("Y-m-d"));

        $dueText = "Due: ";
        if($date < $now && $date > 1) {
            $panel_class = "danger";
            $dueText="<strong> OVERDUE!!: </strong>";
        }

        
        if($job->complete){
            $icon = "glyphicon-check";
            $panel_class = "default";
            $completeText = "Completed by $job->user";
            if ($job->CompletedBy!="" && $job->user!="EVERYONE"){
                $completeText = "Completed by $job->CompletedBy";
            }
        } else {
            $completeText="";
        }

        $createdBy = \DTD\user::get_user($job->CreatedBy)->name;
        if ($createdBy==''){
            $createdBy="Somebody???";
        }
        $completedOn="";
        
        if ($job->CompletedOn!='0000-00-00'){
            $completedOn = "at ". date('d F Y', strtotime($job->CompletedOn));
        }
?>
        <div class="panel panel-<?php echo $panel_class; ?>">
            <div class="panel-heading clearfix">
                <h4 class="panel-title">
                    <span class="glyphicon glyphicon-th-large pull-left <?php echo $icon; ?>" style="font-size: 35px; padding-right: 10px;"></span>
                    <?php echo "<a class=\"btn-block\" data-toggle=\"collapse\" data-parent=\"#accordionJobList\" href=\"#collapse" . $count . "\">"; ?>
                    <?php echo   strtoupper($job->name); ?>
                    <div class="pull-right btn btn-sm btn-<?php echo $user_class; ?>">
                        <?php echo   strtoupper($job->user); ?>
                    </div>
                    <br>
                    <?php echo $dueText . $DueDate ?>
                    <?php echo "</a>"; ?>
                </h4>
            </div>
            <div id="collapse<?php echo $count; ?>" class="panel-collapse collapse">
<!--            <div id="collapse1" class="panel-collapse collapse in">-->

                <div class="panel-body">
                    <table class="table">
                        <tr class="<?php echo $panel_class; ?>">
                            <td>Created on: <?php echo $CreatedOn; ?></td>
                            <td>Created by: <?php echo $createdBy ?></td>
                            <td><?php echo $completeText ?></td>
                            <td><?php echo $completedOn ?></td>
                        </tr>
                    </table>
                    <p class="job-details">
                        <?php echo nl2br( $job->details); ?>
                    </p>
                </div>
                <div class="panel-footer">
                    <button class="btn-default btn-del-job pull-right" job-id="<?php echo $job->id ?>"
                            data-toggle="modal">
                        <span class="glyphicon glyphicon-trash"></span>
                        Delete Job
                    </button>
                    <button class="btn-default btn-edit-job pull-right" job-id="<?php echo $job->id ?>"
                            data-toggle="modal">
                        <span class="glyphicon glyphicon-edit"></span>
                        Edit Job
                    </button>
                    <form action="actions_post/toggleComplete_job.php" method="post">
                        <button type="submit" class="btn-default btn-complete-job pull-right" name="complete-job-id" value="<?php echo $job->id ?>"
                                data-complete="<?php echo $job->complete ?>">
                            <span class="glyphicon <?php echo $icon; ?>"></span>
                            Job Completed
                        </button>
                    </form>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
<?php
        $count = $count + 1;
    }
} else {
    echo "<h2>There are currently no jobs</h2>";
}

?>
</div>