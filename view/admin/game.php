<?php

$__app = $GLOBALS['app'];
$__uid = $app->router->getParam('uid');
$__game = $__app->rc->game;
$__roster = $__app->rc->roster;
$__attendance = $__app->rc->attendance;


$__teams = explode('vs.', $__game->summary);
$__home = $__teams[0];
$__away = $__teams[1];

// TODO: maybe not show "going" and "not going" and instead use a class to highlight the color of the namge
// TODO: green = going
// TODO: red = not going
// TODO: gray = unknown
// TODO: update the unknown button to gray

// TODO: OR do this:
// TODO: blue = male
// TODO: pink = female

?>
<script>

</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h1 class="card-header">
                    Admin > Game
                </h1>
                <div class="card-body">

                    <div class="alert alert-info">
                        <div>
                            <?= $__game->datetime->format('F d, Y') ?> at
                            <?= $__game->datetime->format('g:i A') ?>
                        </div>
                        <div>
                            <?= $__home ?> vs
                            <?= $__away ?> at
                            <a href="<?= Locations::getGoogleMapLink( trim($__game->location) ) ?>/" target="_blank">
                                <?= $__game->location ?>
                                (<?= Locations::getFieldSurface($__game->location) ?>)
                            </a>
                        </div>
                    </div>

                    <?php foreach($__roster as $key => $user): ?>

                        <?php $__isGoing = Schedule::getUserAttendanceForGame($user, $__game, $__attendance); ?>

                        <div class="row pt-3 pb-3 border-bottom roster-user"
                             data-firstname="<?= $user->firstname ?>"
                             data-lastname="<?= $user->lastname ?>"
                             data-isgoing="<?= $__isGoing ?>"
                             data-rosterid="<?= $user->id ?>"
                             data-uid-<?= $__uid ?>>

                            <div class="col-12 col-md-2 mb-3">
                                <?php if($__isGoing === '0'): ?>
                                    <span class="text-danger">Not Going</span>
                                <?php elseif($__isGoing === '1'): ?>
                                    <span class="text-success">Going</span>
                                <?php else: ?>
                                    <span class="text-warning">Unknown</span>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-md-2 mb-3">
                                <span class="<?= $user->gender === 'M' ? 'text-male' : 'text-female'?>">
                                    <?= $user->firstname ?>
                                    <?= $user->lastname ?>
                                </span>
                            </div>
                            <div class="col-12 col-md-8 mb-3">
                                <div class="row">
                                    <div class="col-12 col-md-6 col-xl-4 mb-3">
                                        <!-- old
                                        <button class="btn btn-primary btn-block btn-change-attendance">
                                            Change Attendance
                                        </button>
                                        -->
                                        <?php
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
                                        <form id="form-set-attendance-<?= $key ?>"
                                            class="form-set-attendance"
                                            action="/admin/schedule/game/<?= $__uid ?>/attendance/update"
                                            method="post"
                                            data-type="json"
                                            onsubmit="return false;">

                                            <div class="dropdown">
                                                <button class="btn <?= $__isGoingClass ?> btn-block dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <?= $__isGoingText ?>
                                                </button>
                                                <div class="dropdown-menu btn-block" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item text-success" href="javascript:void(0)" data-value="1">Going</a>
                                                    <a class="dropdown-item text-danger" href="javascript:void(0)" data-value="0">Not Going</a>
                                                </div>
                                            </div>

                                            <input type="hidden" name="rosterId" value="<?= $user->id ?>">
                                            <input type="hidden" name="isGoing" value="" class="isGoing">
                                            <button class="btn btn-primary btn-submit d-none">
                                                Submit
                                            </button>
                                        </form>

                                        <script>
											$(document).ready(function(){
												var formId = 'form-set-attendance-' + <?= $key ?>;
												var form = $('#' + formId);
												var btn = form.find('.btn').first();

												var doOnSuccess = function(){

													var v = form.find('.isGoing');

													btn.removeClass('btn-primary')
													.removeClass('btn-success')
													.removeClass('btn-danger')
													.removeClass('btn-warning');

													if(v.val() === '0'){
														btn.addClass('btn-danger');
														btn.text('Not Going');
													}else if(v.val() === '1'){
														btn.addClass('btn-success');
														btn.text('Going');
													}

												};

												var oForm = {
													formId: formId,
													doOnSuccess: doOnSuccess
												};

												var f = new JVForm(oForm);

												form.find('.dropdown-item').click(function(){
													console.log('click');
													form.find('.isGoing').val( $(this).data('value'));
													form.find('.btn-submit').trigger('click');
												});
											});
                                        </script>

                                    </div>
                                    <div class="col-12 col-md-6 col-xl-4 mb-3">
                                        <form id="form-remind-by-sms-<?= $key ?>" action="/admin/schedule/game/<?= $__uid ?>/attendance/remind/sms/<?= $user->id ?>" method="post" data-type="json" onsubmit="return false;" >
                                            <button class="btn btn-primary btn-block" id="btn-submit">
                                                Remind by SMS
                                            </button>
                                        </form>
                                        <script>
                                            $(document).ready(function(){
                                                var oForm = {
                                                    formId: 'form-remind-by-sms-' + <?= $key ?>
                                                };
                                                var f = new JVForm(oForm);
                                            });
                                        </script>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-4 mb-0">
                                        <form id="form-remind-by-email-<?= $key ?>" action="/admin/schedule/game/<?= $__uid ?>/attendance/remind/email/<?= $user->id ?>" method="post" data-type="json" onsubmit="return false;" >
                                            <button class="btn btn-primary btn-block" id="btn-submit">
                                                Remind by Email
                                            </button>
                                        </form>
                                        <script>
											$(document).ready(function(){
												var oForm = {
													formId: 'form-remind-by-email-' + <?= $key ?>
												};
												var f = new JVForm(oForm);
											});
                                        </script>
                                    </div>
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>
</div>