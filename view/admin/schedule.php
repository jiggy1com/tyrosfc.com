<?php

$__isAdminView = true;
$__schedule = $GLOBALS['app']->rc->schedule;
$__now = new DateTime();

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h1 class="card-header">
                    Admin > Schedule
                </h1>
                <div class="card-body">

                    <?php include('view/partial/schedule.php') ?>

                    <?php
                    /*


                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-2 card-header">
                                Date
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-2 card-header">
                                Home Team
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-2 card-header">
                                Away Team
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 card-header">
                                Location
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 card-header">
                                Attendance
                            </div>
                        </div>

                        <?php foreach($__schedule as $key => $obj): ?>
                            <?php

                            $teams = explode('vs.', $obj->summary);
                            $home = $teams[0];
                            $away = $teams[1];

                            $date = $obj->datetime->format("F d, Y");
                            $time = $obj->datetime->format('g:i A');

                            ?>
                            <div class="row bg-dark mt-5">
                                <div class="col-12 pt-3 pb-3">
                                    Week <?= $key+1 ?> <!--(<?= $obj->uid ?>)-->
                                </div>
                            </div>
                            <div class="row mt-3 mb-3">
                                <div class="col-xs-12 col-sm-12 col-md-2">
                                    <?= $date; ?> at <?= $time; ?>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-2">
                                    <?= $home ?>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-2">
                                    <?= $away ?>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <a href="<?= Locations::getGoogleMapLink( trim($obj->location) ) ?>/" target="_blank">
                                        <?= $obj->location ?>
                                    </a>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-3">
                                    <a href="/admin/schedule/game/<?= $obj->uid ?>/attendance" class="btn btn-primary btn-block">
                                        View Attendance
                                    </a>
                                    <a href="/admin/schedule/game/<?= $obj->uid ?>/lineup" class="btn btn-primary btn-block">
                                        View Lineup
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>

                    */
                    ?>


                </div>
            </div>
        </div>
    </div>
</div>