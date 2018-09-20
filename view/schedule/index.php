<?php
    $__isAdminView = false;
    $__schedule = $GLOBALS['app']->rc->scheduleModel;
    $__attendance = $GLOBALS['app']->rc->attendance;
    $__now = new DateTime();
?>


<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h1 class="card-header">
                   Schedule
                </h1>
                <div class="card-body">

                    <?php include('view/partial/schedule.php') ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
# spacing for scrolling
for($i=0;$i<20;$i++){
    echo "<p>&nbsp;</p>";
}
?>