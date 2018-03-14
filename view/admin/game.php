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

                    <h1>Please note that not all the buttons on this page are wired up. Do not expect desired results until this message goes away, but you can play around.</h1>

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
                                        <button class="btn btn-primary btn-block btn-change-attendance">
                                            Change Attendance
                                        </button>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-4 mb-3">
                                        <a href="/admin/schedule/game/<?= $__uid ?>/attendance/remind/sms/<?= $user->id ?>" class="btn btn-primary btn-block">
                                            Remind by SMS
                                        </a>
                                    </div>
                                    <div class="col-12 col-md-6 col-xl-4 mb-0">
                                        <a href="/admin/schedule/game/<?= $__uid ?>/attendance/remind/email/<?= $user->id ?>" class="btn btn-primary btn-block">
                                            Remind by Email
                                        </a>
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

<?php include('view/admin/game-modal-attendance.php'); ?>
