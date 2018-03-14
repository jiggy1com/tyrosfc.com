<div class="container-fluid">

    <div class="d-none d-md-block">
        <div class="row d-md">
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
            <div class="col-xs-12 col-sm-12 col-md-3 pb-3">
                <a href="<?= Locations::getGoogleMapLink( trim($obj->location) ) ?>/" target="_blank">
                    <?= $obj->location ?>
                    (<?= Locations::getFieldSurface( trim($obj->location) ) ?>)
                </a>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3">


                <?php if( $__now->getTimestamp() < $obj->datetime->getTimeStamp()): ?>

                    <?php # Admin Features ?>
                    <?php if($__isAdminView): ?>
                        <a href="/admin/schedule/game/<?= $obj->uid ?>/attendance" class="btn btn-primary btn-block">
                            View Attendance
                        </a>
                        <a href="/admin/schedule/game/<?= $obj->uid ?>/lineup" class="btn btn-primary btn-block">
                            View Lineup
                        </a>
                    <?php endif; ?>

                    <?php # Frontend Features ?>
                    <?php if(!$__isAdminView): ?>

                        <?php
                        $__isGoing = Schedule::isGoing($__attendance, $obj->uid);

                        if($__isGoing === null){
                            $__isGoingClass = 'btn-primary';
                        }else if($__isGoing === true){
                            $__isGoingClass = 'btn-success';
                        }else if($__isGoing === false){
                            $__isGoingClass = 'btn-danger';
                        }else{
                            $__isGoingClass = 'btn-warning';
                        }

                        if($__isGoing === null){
                            $__isGoingText = 'Set Attendance';
                        }else if($__isGoing === true){
                            $__isGoingText = 'Going';
                        }else if($__isGoing === false){
                            $__isGoingText = 'Not Going';
                        }else{
                            $__isGoingText = "Something F'd Up!";
                        }

                        ?>
                        <a href="/schedule/game/<?= $obj->uid ?>/attendance" class="btn <?= $__isGoingClass ?> btn-block">
                            <?= $__isGoingText ?>
                        </a>
                    <?php endif; ?>

                <?php else:?>
                    <button class="btn btn-primary disabled text-center btn-block">
                        This game has passed.
                    </button>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>

</div>
