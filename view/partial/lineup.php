<script>

	$(document).ready(function(){

		var oForm = {
			formId: 'form-lineup',
			doOnSuccess: function(){
				var clone = $('.alert').clone();
				$('.alert').remove();
				$('.btn-save-container').append(clone);
				setTimeout(function(){
					$('.alert').slideUp();
                },1500);
			}
		};

		var lineupForm = new JVForm(oForm);

		var formations = [
			// def, mid, strikers
			'1x3x4x2',
			'1x3x3x3'
		];

		// game version
		var attendance = '<?= json_encode($__attendance) ?>';
		attendance = JSON.parse(attendance);

		var game = '<?= $__game ?>';
		game = JSON.parse(game);

		var lineups = game.lineups;

		// testing
		var oGame = {
			elementId: 'game-container',
			attendance: attendance,
			totalLineups: 8,
			lineups: lineups,
			$__isAdminView: <?= $__isAdminView ? 'true' : 'false' ?>,
			$__isPrintLayout: <?= $__isPrintLayout ? 'true' : 'false' ?>
		};
		var g = new Game(oGame);
        g.setAttendance(attendance)
		.setLineups(game.lineups)
		.init();
        //g.render();

        console.log('g', g);

	});


</script>

<!-- GAME -->
<div id="game-container">

    <form action="/admin/schedule/game/<?= $__uid ?>/lineup/update" method="post" data-type="json" id="form-lineup" onsubmit="return false">

        <input type="hidden" name="game" id="game" value="">

        <?php if($__isAdminView): ?>
            <?php if($__app->layout !== 'print'): ?>
                <div class="bg-dark text-center pt-3 pb-3 fixed-bottom btn-save-container">
                    <div class="row ">
                        <div class="col-12">
                            <button class="btn btn-primary">
                                Save
                            </button>

                            <a class="btn btn-info" target="_blank" href="/admin/schedule/game/<?= $__uid ?>/lineup/print">
                                Print
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

    </form>

</div>

<?php if($__app->layout === 'print'): ?>
    <script>
        $(document).ready(function(){
        	window.print();
        });
    </script>
<?php endif; ?>
