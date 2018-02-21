<?php
// we want error messages! 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$debug = true;
?>
<a class="btn btn-sm btn-warning" data-toggle="collapse" data-target="#error-stats">
    Error reporting enabled. Click here for more info...
</a>
<pre id="error-stats" class="collapse">
    <?php
    echo "SESSION: ";
    print_r($_SESSION);
    echo "GET: ";
    print_r($_GET);
    echo "POST: ";
    print_r($_POST);
    echo "Current folder: " . getcwd();
    ?>
</pre>
