<?php
$__activeRoster = $GLOBALS['app']->rc->activeRoster;
$__inActiveRoster = $GLOBALS['app']->rc->inActiveRoster;

?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h1 class="card-header">
                    Admin > Roster
                </h1>
                <div class="card-body">
                    <div class="row">

                        <?php $__roster = $__activeRoster; ?>
                        <?php include('view/partial/roster.php') ?>

                        <?php $__roster = $__inActiveRoster ?>
                        <?php include('view/partial/roster.php') ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
