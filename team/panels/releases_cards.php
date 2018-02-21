<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 24/11/2017
 * Time: 10:30 AM
 */

require_once dirname(__FILE__) . "/" . "../lib/session_controller.php";
$current = new \DTD\session_controller();
$releases = $current->project->releases();

?>
<h1><?php echo $current->project->name ?> releases</h1>
<div class="clearfix" style="margin-bottom: 10px;">
    <button type='button' class='btn btn-default pull-left' data-toggle='modal' data-target='#CreateRelease-Modal'>
        Create new Release / Version...</button>
</div>
<?php

if (!$releases) {
    echo "<h3 style='text-align: center'>There is no release schedule for this Project.<br>";
    if($current->user->id == $current->project->owner_id || $current->user->is_god()){
        echo "You should create a Release / Version.</h3>";
    } else {
        $owner = \DTD\user::get_user($current->project->owner_id);
        echo "Ask the project owner ". $owner->display() . " to create a new release/version of the project.</h3>";
    }
} else {
    $closed = (empty($release->closed))? $closed=" is still ongoing...": "was closed on " . $release->closed;
    $owner_controls = "";  //you cant modify the release if you dont own the project or your not GOD :P...

    foreach ($releases as $release){
        if($current->project->owner_id == $current->user->id || $current->user->is_god()){
            $owner_controls = "<button rid='" . $release->id .
                "' jobCount='" . $release->job_count() .
                "' releaseName='" . $release->name .
                "' type='button' class='btn delete-release btn-danger pull-right'>
                    <span class='glyphicon glyphicon-remove'></span> Delete...
                </button>" .
                "<button rid='" . $release->id .
                "' releaseName='" . $release->name .
                "' type='button' class='btn edit-release btn-default pull-right'>
                    <span class='glyphicon glyphicon-edit'></span> Edit...
                </button>";
        }
        echo "<div class=''>
        <div class='panel panel-default'>
            <div class='panel-heading'>
                <form action='actions_post/open_release.php' method='post'>
                    <h2>Release: " . $release->name . "</h2>

                    <input type='hidden' name='open_release' value='" . $release->id . "'>
                                        
                    <button class='btn btn-info pull-right' type='submit' name='open_rid_" . $release->id . "'>
                    <span class='glyphicon glyphicon-folder-open left'></span>  View jobs for " . $release->name . " </button>
                    
                </form>
            </div>
            <div class='panel-body'>
                This release commenced on: " . $release->opened . " and $closed with " . $release->job_count() . " jobs. 
            </div>
            <div class='panel-footer clearfix'>
                $owner_controls
            </div>
        </div>
    </div>";
    }
}


?>


<!-- Modal for new/create -->
<div id="CreateRelease-Modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="actions_post/add_release.php" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Create new Release Schedule</h4>
                </div>

                <div class="modal-body">
                    <label>Release / Version name / number:</label>
                    <input type="text" class="form-control" name="name" placeholder="Release or Version number/name"><br>
                    <input type="hidden" name="pid" value="<?php echo $current->project->id ?>">
                    <p class="alert alert-info"><strong>NOTE:</strong><br>
                        All unfinished jobs from previous release of <?php echo $current->project->name ?> will be pushed forward to this new release schedule.<br>
                        All jobs for this project that do not have a release number (eg. Archived from a deleted release where jobs were not also deleted) will also be pushed forward.</p>
                </div>

                <div class="modal-footer">
                    Are you sure?
                    <input type="submit" class="btn btn-default" value="Yes do it!">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal for edit -->
<div id="EditRelease-Modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="actions_post/edit_release.php" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Release Schedule</h4>
                </div>

                <div class="modal-body">
                    <label>Release / Version name / number:</label>
                    <input id="Edit-Release-Name" type="text" class="form-control" name="name" placeholder="Release or Version number/name"><br>
                    <input id="Edit-Release-Id" type="hidden" name="rid" value="0">
                </div>

                <div class="modal-footer">
                    Save changes?
                    <input type="submit" class="btn btn-success" value="Save">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for DELETE -->
<div id="DeleteRelease-Modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="actions_post/delete_release.php" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delete Release Schedule</h4>
                </div>

                <div class="modal-body">
                    <h2 id="delete_release-title">Delete xxxxxxxx from the release schedule?</h2>
                    <input id="delete_rid_num" type="hidden" name="rid" value="">
                    <input type="checkbox" name="deleteAllJobs" checked>
                    Delete all the jobs as well. <br> (NOTE: un-deleted jobs will come back when a new release is created.)
                </div>

                <div class="modal-footer">
                    Are you sure?
                    <input type="submit" class="btn btn-danger" value="Yes do it!">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(function () {

        $(".delete-release").click(function () {
            var rid = $(this).attr("rid");
            var jobCount = $(this).attr("jobCount");
            var releaseName = $(this).attr("releaseName");
            $("#delete_rid_num").val(rid);
            $("#delete-release-title").html("Release " + releaseName + " has " + jobCount + " jobs inside it!");
            $('#DeleteRelease-Modal').modal('show');
        });

        $(".edit-release").click(function () {
            var rid = $(this).attr("rid");
            var releaseName = $(this).attr("releaseName");
            $("#Edit-Release-Name").val(releaseName);
            $("#Edit-Release-Id").val(rid);
            $('#EditRelease-Modal').modal('show');
        });
    })
</script>