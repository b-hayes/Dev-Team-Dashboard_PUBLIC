<?php
/**
 * Created by PhpStorm.
 * User: Brad
 * Date: 27/11/2017
 * Time: 2:29 AM
 */

if(!isset($_SESSION)){
    session_start();
}

?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<div class="panel panel-info">
    <form action="./db/add_hours.php" method="post">
        <div class="panel-heading">
            <h2>Log your hours for:</h2>
            <h3><?php echo $_SESSION["project"]["name"] . " > Release " . $_SESSION["release"]["name"] ?></h3>
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label>Start Time:</label>
                <input id="hours-start" class="form-control timepicker" type="time" name="start" value="<?php echo date("H:i"); ?>">
            </div>
            <div class="form-group">
                <label>Finish Time:</label>
                <input id="hours-end" class="form-control timepicker" type="time" name="end" value="<?php echo date("H:i"); ?>">
                <?php echo date("H:i:s"); ?>
            </div>
        </div>
        <div class="panel-footer clearfix">
            <input id="hours-submit" type="submit" class="btn btn-success pull-right">
            <input type="reset" class="btn btn-default pull-right">
            <p id="hours-calc" style="text-align: center">0 Hours</p>
        </div>
    </form>
</div>

<?php
include "./lib/database.php";
$conn = new \DTD\database();

?>

<script>
    $(function () {
        var time = new Date($.now());
        var h = time.getHours()

        function roundMinutes(date) {

            date.setHours(date.getHours() + Math.round(date.getMinutes()/60));
            date.setMinutes(0);

            return date;
        }
        var nearestHour = roundMinutes(time);

//        $('.timepicker').timepicker({
//            timeFormat: 'h:mm p',
//            interval: 15,
//            minTime: '0:00AM',
//            maxTime: '11:45PM',
//            defaultTime: nearestHour,
//            startTime: '10:00',
//            dynamic: true,
//            dropdown: true,
//            scrollbar: true
//        });
    });
</script>