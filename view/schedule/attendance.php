<?php

$__isAdminView = false;
$__isPrintLayout = false;

$__app = $GLOBALS['app'];

$__uid = $__app->router->getParam('uid');

// Team Attendance
$__teamAttendance = $__app->rc->teamAttendance;

// Your Attendance
$__userAttendance = $__app->rc->userAttendance;
$__isGoing = Schedule::isGoing($__userAttendance, $__uid); // $__attendance
$__gameDate = $__app->rc->game;
$__teams = explode('vs.', $__gameDate->summary);
$__home = $__teams[0];
$__away = $__teams[1];

// lineup
$__attendance = $__app->rc->attendance;
$__lineup = $__app->rc->lineup;
$__game = $__lineup[0]->game;

// totals
$__men = 0;
$__women = 0;
$__unknown = 0;

foreach($__teamAttendance as $row){
    if($row->gender === 'M' && $row->isgoing == 1){
        $__men++;
    }else if($row->gender == 'F' && $row->isgoing == 1){
        $__women++;
    }else if($row->isgoing == '' || $row->isgoing == null){
        $__unknown++;
    }
}

?>

<script>
    $(document).ready(function(){

    	var selectedBtn = null;
    	var $btnReturnToSchedule = $('#btn-return-to-schedule');
		    $btnReturnToSchedule.hide();

		var $btn = $('#form-update-attendance').find('.btn');
		var $isGoing = $('#isGoing');

		$btn.click(function(){
			selectedBtn = $(this);
			$isGoing.val( $(this).data('value') );
			f.doSubmit();
		});

		var doOnSuccess = function(){
			$('.alert-warning').remove();
			$btn.each(function(){
				console.log('btn', $(this));
				$(this).find('svg').remove();
            });
			selectedBtn.find('span').before("<span class='fa fa-check'></span> ");

			// show the return link for lazy folks
			$btnReturnToSchedule.show();
		};

		var oForm = {
			formId: 'form-update-attendance',
			doOnSuccess: doOnSuccess
		};

		var f = new JVForm(oForm);
    });
</script>

<div class="container-fluid mb-5">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h1 class="card-header">
                    Your Attendance
                </h1>
                <div class="card-body">

                    <div class="alert alert-info">
                        <div>
                            <?= $__gameDate->datetime->format('F d, Y') ?> at
                            <?= $__gameDate->datetime->format('g:i A') ?>
                        </div>
                        <div class="">
                            <?= $__home ?> vs
                            <?= $__away ?> at
                            <a href="<?= Locations::getGoogleMapLink( trim($__gameDate->location) ) ?>/" target="_blank">
                                <?= $__gameDate->location ?>
                                (<?= Locations::getFieldSurface($__gameDate->location) ?>)
                            </a>
                        </div>
                    </div>

                    <?php if($__isGoing === null):?>
                        <div class="alert alert-warning">
                            You have not provided your attendance for this game.
                            Please click the green or red button below to set your attendance.
                        </div>
                    <?php endif; ?>

                    <form action="/schedule/game/update-game-attendance" method="post" id="form-update-attendance" data-type="json" onsubmit="return false">

                        <input type="hidden" name="uid" id="uid" value="<?= $__uid ?>" />
                        <input type="hidden" name="isGoing" id="isGoing" value="<?= $__isGoing ?>" />

                        <?php /*
                        <div class="form-group">
                            <label>Attendance</label>
                            <select class="form-control" name="isGoing">
                                <option value=""  <?= $__isGoing == null ? 'selected' : null ?>></option>
                                <option value="1" <?= $__isGoing == '1' ? 'selected' : null ?>>Going</option>
                                <option value="0" <?= $__isGoing == '0' ? 'selected' : null ?>>Not Going</option>
                            </select>
                        </div>

                        <button class="btn btn-primary">
                            Submit
                        </button>
                        */ ?>

                        <div class="row">
                            <div class="col-12 col-md-4 pb-3">
                                <button class="btn btn-success btn-block" data-value="1">
                                    <?php if($__isGoing == '1'): ?>
                                        <span class="fa fa-check"></span>
                                    <?php endif; ?>
                                    <span>Attending</span>
                                </button>
                            </div>
                            <div class="col-12 col-md-4 pb-3">
                                <button class="btn btn-danger btn-block" data-value="0">
                                    <?php if($__isGoing == '0'): ?>
                                        <span class="fa fa-check"></span>
                                    <?php endif; ?>
                                    <span>Not Attending</span>
                                </button>
                            </div>
                            <div class="col-12 col-md-4 pb-3">
                                <button class="btn btn-warning btn-block" data-value="">
                                    <?php if($__isGoing == '' || $__isGoing == null): ?>
                                        <span class="fa fa-check"></span>
                                    <?php endif; ?>
                                    <span>Unknown Attending</span>
                                </button>
                            </div>
                        </div>

                    </form>

                    <div class="row">
                        <div class="col-12">
                            <a href="/schedule" class="btn btn-secondary btn-block" id="btn-return-to-schedule">
                                Return to Schedule
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mb-5">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h1 class="card-header">
                    Team Attendance
                </h1>
                <div class="card-body">

                    <div class="row pb-3">
                        <div class="col-12">
                            <?= $__men ?> Men
                        </div>
                        <div class="col-12">
                            <?= $__women ?> Women
                        </div>
                        <div class="col-12">
                            <?= $__unknown ?> Unknown
                        </div>
                    </div>

                    <?php foreach($__teamAttendance as $idx => $obj): ?>
                        <div class="row pt-3 pb-3 <?= $idx % 2 !== 1 ? Schedule::BG_EVEN : '' ?>">
                            <div class="col-12 col-md-10">
                                <?php $__textClass = $obj->gender === 'M' ? 'text-male' : ($obj->gender === 'F' ? 'text-female' : ''); ?>
                                <span class="<?= $__textClass ?>">
                                    <?= $obj->firstname ?>
                                    <?= $obj->lastname ?>
                                </span>
                            </div>
                            <div class="col-12 col-md-2">
                                <span class="<?= Schedule::dspIsGoingAsTextCSSClass($obj->isgoing) ?> ">
                                    <?= Schedule::dspIsGoingAsText($obj->isgoing) ?>
                                </span>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid mb-5">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h1 class="card-header">
                    Lineup
                </h1>
                <div class="card-body">

                    <?php include('view/partial/lineup.php'); ?>

                </div>
            </div>
        </div>
    </div>
</div>