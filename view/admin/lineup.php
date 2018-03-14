<?php

// get IDE to not think $__app does not exist
$__app = $GLOBALS['app'];

// admin view
$__isAdminView = true;

// print layout
$__isPrintLayout = $__app->layout === 'print';

// template vars
$__uid = $__app->router->getParam('uid');
$__attendance = $__app->rc->attendance;
$__lineup = $__app->rc->lineup;
$__game = $__lineup[0]->game;


?>

<div id="quarter-template">
    <div class="row quarter">
        <div class="col-10">
            <div class="starters"></div>
        </div>
        <div class="col-2">
            <div class="subs"></div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <?php if($__app->layout !== 'print'): ?>
                    <h1 class="card-header">
                        Admin > Lineup
                    </h1>
                <?php endif; ?>

                <div class="card-body">

                    <?php include('view/partial/lineup.php'); ?>

                </div>
            </div>
        </div>
    </div>
</div>

