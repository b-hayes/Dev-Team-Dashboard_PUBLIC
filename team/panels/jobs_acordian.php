<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 17/05/2017
 * Time: 7:33 AM
 */
require_once dirname(__FILE__) . "/" . "../lib/session_controller.php";
$current = new \DTD\session_controller();
?>

<!-- Modal for new/create -->
<div id="CreateJob-Modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="actions_post/add_job.php" method="post" id="form-new-job">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Create new Job / Request</h4>
                </div>

                <div class="modal-body">
                    <label>Job Name:</label>
                    <input type="text" class="form-control" name="name"><br>
                    <label>Details:</label>
                    <div class="pull-right btn btn-primary btn-pre-tags">Insert PRE formatted Code/Text</div>
                    <textarea name="details" form="form-new-job" id="newjobdetails" class="form-control" rows="10" cols="80"></textarea><br>
                    <label for="user">Assign Task to:</label>
                    <select class="form-control" name="user">
                        <?php
                        // Select list of users
                        include dirname(__FILE__) . "/" . "../panels/userSelectList.php";
                        ?>
                    </select>
                    <label for="due-date">Due Date: (optional) </label>
                    <input name="due-date" class="form-control" type="date">
                </div>

                <div class="modal-footer">
                    <button type="submit" name="create-job-submit" class="btn btn-default">Create</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for edit -->
<div id="EditJob-Modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="actions_post/edit_job.php" method="post" id="form-edit-job">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Create new Job / Request</h4>
                </div>

                <div class="modal-body">
                    <input hidden id="edit-job-id" name="job-id" value="this value is set by the edit button on a job"/>
                    <label>Job Name:</label>
                    <input type="text" class="form-control" name="name" id="edit-job-name"><br>
                    <label>Details:</label>
                    <div class="pull-right btn btn-primary btn-pre-tags">Insert PRE formatted Code/Text</div>
                    <textarea name="details" form="form-edit-job" id="edit-job-details" class="form-control" rows="10" cols="80"></textarea><br>
                    <label for="user">Assign Task to:</label>
                    <select class="form-control" name="user" id="edit-job-user">
                        <?php
                        // Select list of users
                        include dirname(__FILE__) . "/" . "../panels/userSelectList.php";
                        ?>
                    </select>
                    <label for="due-date">Due Date: (optional) </label>
                    <input name="due-date" class="form-control" type="date" id="edit-job-dueDate">
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for DELETE -->
<div id="DeleteJob-Modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content warning">
            <form action="actions_post/delete_job.php" method="post">
                <input hidden id="del-job-id" name="job-id" value="this value is set by the delete button on a job"/>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delete Job / Request</h4>
                </div>

                <div class="modal-body">
                    <P>Are you sure you want to delete this Job?</P>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-warning">Yes Delete it</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<h1>JOBS / REQUESTS</h1>
<a class="btn btn-default" data-toggle="modal" data-target="#CreateJob-Modal">
    <span class="glyphicon glyphicon-plus"></span>
    New Job
</a>
<a class="btn btn-default" data-toggle="collapse" data-target="#job-stats">
    <span class="glyphicon glyphicon-expand"></span>
    Show Stats & Legend
</a>
<div id="job-stats" class="collapse">
    <p>
        Legend:
        <span class="btn-primary"> Jobs not for you </span>
        <span class="btn-default"> Completed Jobs </span>
        <span class="btn-success"> Your Jobs </span>
        <span class="btn-info"> Jobs for Everyone </span>
        <span class="btn-warning"> Unassigned Jobs </span>
        <span class="btn-danger"> Overdue Jobs </span>
    </p>

    <?php include dirname(__FILE__) . "/" . "../panels/job_tally.php"; ?>
</div>
<div id="jobList" release_rid="<?php echo $current->release->id ?>">
    <!--       Accordion of jobs gets inserted here         -->
    <?php include dirname(__FILE__) . "/" . "../panels/job_list.php"; ?>
</div>

<script>
    $(function () {
       $(".btn-pre-tags").click(function () {
           $(this).next("textarea").val($(this).next("textarea").val() + "\n<PRE>\nYour formatted code/text here...\n</PRE>");
       });

       $(".btn-del-job").click(function () {
          var jobId = $(this).attr("job-id");
          $("#del-job-id").val(jobId);
           $("#DeleteJob-Modal").modal('show');
       });

       $(".btn-edit-job").click(function () {
           var jobId = $(this).attr("job-id");
           $.ajax({
               url: "actions_ajax/getJob.php",
               type: 'POST',
               data: { id: jobId },
               success: function(result){
                   if (result != "Failed to get job" || result != "No job id received"){
                       var job = $.parseJSON(result);
                       //setup the modal and display it..
                       $("#edit-job-id").val(job.id);
                       $("#edit-job-name").val(job.name);
                       $("#edit-job-details").val(job.details);
                       $("#edit-job-user").val(job.user);
                       if(job.DueDate ==  "1970-01-01"){
                           job.DueDate = "";
                       }
                       $("#edit-job-dueDate").val(job.DueDate);
                       $('#EditJob-Modal').modal('show');
                   } else {
                       alert(result);
                   }
               }
           });
       });
    });
</script>
