<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 24/11/2017
 * Time: 5:00 AM
 */

require_once dirname(__FILE__) . "/" . "../lib/session_controller.php";
$current = new \DTD\session_controller();
$projects = \DTD\project::get_all();

function td($text=''){
    echo "<td>" . $text . "</td>";
}
function th($text=''){
    echo "<th>" . $text . "</th>";
}

?>
<h1>SELECT A PROJECT</h1>
<div class="clearfix">
    <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#CreateProject-Modal">Create new Project...</button>
</div>
<?php
if (!$projects) {
    echo "<h3 style='text-align: center'>There are no projects visible to you.</h3>";
} else {
    foreach ($projects as $project) {
        //skip this project if the user cant see it
        if(!$current->user->can_access($project)){
            continue;
        }
        $ownerControlls = "";
        if($current->user->id == $project->owner_id || $current->user->is_god()){
            //current user owns this project
            $ownerControlls = "
            <div class='clearfix'>
                <button  project_id='$project->id' class='pull-right btn btn-default btn-edit-project'>
                    Edit Project details...
                </button>
                <button project_id='$project->id' class='pull-right btn btn-danger btn-delete-project'>
                    <span class='glyphicon glyphicon-remove'></span> Delete
                </button>
                <button project_id='$project->id' class='pull-right btn btn-default btn-edit-user-access'>
                    <span class='glyphicon glyphicon-eye-open'></span> Change who can see this project...
                </button>                
            </div>
            ";
        }
        echo "<div class='' style='padding-left: 5px;'>
            <div class='panel panel-default'>
                <div class='panel-heading'><h2>" . $project->name ."</h2>
                <form action='actions_post/open_project.php' method='post'>
                    <input type='hidden' name='open_project' value='" . $project->id . "'>
                    <button class='btn btn-info pull-right' type='submit' name='open_pid_" .  $project->id . "'>
                        Open Project <span class='glyphicon glyphicon-arrow-right'></span>
                    </button>
                </form>
                </div>
                <div class='panel-body'>
                    <div id='description'>" . $project->description . "</div>
                </div>
                <div class='panel-footer clearfix'>
                    <div class=''>
                        Created: " . $project->created .
                        "<br>Owner: " . \DTD\user::get_user($project->owner_id)->display() . "
                    </div>
                    <div class=''>$ownerControlls</div>
                </div>
            </div>
        </div>
            ";
    }
}
?>

<!-- Modal for new/create -->
<div id="CreateProject-Modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="actions_post/add_project.php" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Create new Project</h4>
                </div>

                <div class="modal-body">
                    <label>Project Name:</label>
                    <input type="text" class="form-control" name="name"><br>
                    <label>Description:</label>
                    <textarea class="form-control" rows="10" cols="80" name="description"></textarea>
                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-default">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for edit -->
<div id="EditProject-Modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="actions_post/edit_project.php" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Project</h4>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id" id="EditProjectId" value="0">
                    <label>Project Name:</label>
                    <input type="text" class="form-control" name="name" id="EditProjectName"><br>
                    <label>Description:</label>
                    <textarea class="form-control" rows="10" cols="80" name="description" id="EditProjectDescription"></textarea>
                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-default" value="Save Changes">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for user access -->
<div id="UsersInProject-Modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div id="UserAccess-Modal-Content" class="modal-content">

        </div>
    </div>
</div>

<!-- Modal for delete -->
<div id="DeleteProject-Modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="actions_post/delete_project.php" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delete Project</h4>
                </div>

                <div class="modal-body">
                    <input hidden id="delete-project-id" name="project-id" value="this value is set by javascript"/>
                    <p class=" alert alert-danger">
                        WARNING! You will lose everything for the project, all jobs releases etc.<br>
                        This action is permanent and nothing can be recovered again.
                    </p>
                    You must enter your password again to confirm this action.
                    <input type="password" name="password">
                </div>

                <div class="modal-footer">
                    <input type="submit" class="btn btn-danger" value="Destroy Project and eveything it contains!">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function () {
        $(".btn-edit-user-access").click(function () {
            var projectID = $(this).attr("project_id");

            $.ajax({
                url: "panels/set_user_access_modal.php",
                type: 'POST',
                data: { project_id: projectID },
                success: function(result){
                    $("#UserAccess-Modal-Content").html(result);
                    $("#UsersInProject-Modal").modal('show');
                }
            });
        });

        $(".btn-delete-project").click(function () {
            var projectID = $(this).attr("project_id");
            $("#delete-project-id").val(projectID);
            $("#DeleteProject-Modal").modal('show');
        });

        $(".btn-edit-project").click(function () {
            var projectId = $(this).attr("project_id");

            $.ajax({
                url: "actions_ajax/getProject.php",
                type: 'POST',
                data: { id: projectId },
                success: function(result){
                    if (result != "Failed to get project" || result != "No project id received"){
                        var project = $.parseJSON(result);
                        console.log(project);
                        //setup the modal and display it..
                        $("#EditProjectId").val(project.id);
                        $("#EditProjectName").val(project.name);
                        $("#EditProjectDescription").val(project.description);
                        $('#EditProject-Modal').modal('show');
                    } else {
                        alert(result);
                    }
                }
            });
        });
    });
</script>