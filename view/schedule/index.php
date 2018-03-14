<?php
    $__isAdminView = false;
    $__schedule = $GLOBALS['app']->rc->schedule;
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