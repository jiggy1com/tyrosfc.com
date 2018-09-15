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

    <?php $__isCurrentGame = false; ?>

    <?php foreach($__schedule as $key => $obj): ?>
        <?php

        $teams = explode('vs.', $obj->summary);
        $home = $teams[0];
        $away = $teams[1];

        $date = $obj->datetime->format("F d, Y");
        $time = $obj->datetime->format('g:i A');

        if(!$__isCurrentGame){
            $__isCurrentGame = $__now->getTimestamp() < $obj->datetime->getTimeStamp();
        }

        ?>
        <div class="row bg-dark mt-5 <?= $__isCurrentGame ? 'current-game' : '' ?>">
            <div class="col-12 pt-3 pb-3 week">
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

                        <form id="form-set-attendance-<?= $key ?>"
                            class="form-set-attendance"
                            action="/schedule/game/update-game-attendance"
                            method="post"
                            data-type="json"
                            onsubmit="return false;">

                            <div class="dropdown">
                                <button class="btn <?= $__isGoingClass ?> btn-block dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?= $__isGoingText ?>
                                </button>
                                <div class="dropdown-menu btn-block" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item text-success p-3" href="javascript:void(0)" data-value="1">Going</a>
                                    <a class="dropdown-item text-danger p-3" href="javascript:void(0)" data-value="0">Not Going</a>
                                </div>
                            </div>

                            <input type="hidden" name="uid" value="<?= $obj->uid ?>">
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

<script>
    $(document).ready(function(){
		var currentGame = $('.current-game').first();
		var offset = currentGame.offset();
    	currentGame.find('.week').text(currentGame.text() + ' (Next Game)');
		$('body, html').scrollTop( offset.top );
    });
</script>

<?php
# spacing for scrolling
for($i=0;$i<20;$i++){
    echo "<p>&nbsp;</p>";
}
?>