<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <p>You can now click the team name to see the standings/stats for that team.</p>
            <p>The score is listed in color next to the team name. Colors: <span class="text-success">Winning Score</span>, <span class="text-danger">Losing Score</span>, <span class="text-info">Tie Score</span></p>
            <p>The team's current place is listed next to the team name in parentheses, and is updated across the board automatically week by week.</p>
        </div>
    </div>

</div>

<?php foreach($__schedule as $key => $obj): ?>

        <div class="card mb-5 week-<?= $obj->week ?>  <?= $obj->isCurrentGame ? 'current-game' : '' ?>">
            <h1 class="card-header week <?= $obj->bgClass ?>">
                Week <?= $obj->week ?>
            </h1>
            <div class="card-body">

                <div class="row mt-3 mb-3">
                    <div class="col-xs-12 col-sm-12 col-md-2">
                        <div>
                            <?= $obj->date; ?>
                        </div>
                        <div>
                            <?= $obj->time; ?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4">
                        <span class="home">
                            <span class="score">
                            </span>
                            <span class="place">
                            </span>
                            <span class="show-stats <?= $obj->homeClass ?>">
                                <?= $obj->homeTeam ?>
                            </span>
                        </span>
                        vs.
                        <span class="away">
                            <span class="score">
                            </span>
                            <span class="place">
                            </span>
                            <span class="show-stats <?= $obj->awayClass ?>">
                                <?= $obj->awayTeam ?>
                            </span>
                        </span>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 pb-3">
                        <a href="<?= $obj->locationMap ?>">
                            <?= $obj->location ?>
                            (<?= $obj->locationSurface ?>)
                        </a>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3">

                        <?php if($obj->isCurrentGame): ?>
                            <?php # Admin Features ?>
                            <?php if($__isAdminView): ?>
                                <a href="/admin/schedule/game/<?= $obj->gameUid ?>/attendance" class="btn btn-primary btn-block">
                                    View Attendance
                                </a>
                                <a href="/admin/schedule/game/<?= $obj->gameUid ?>/lineup" class="btn btn-primary btn-block">
                                    View Lineup
                                </a>
                            <?php endif; ?>

                            <?php # Frontend Features ?>
                            <?php if(!$__isAdminView): ?>

                                <form id="form-set-attendance-<?= $key ?>"
                                      class="form-set-attendance"
                                      action="/schedule/game/update-game-attendance"
                                      method="post"
                                      data-type="json"
                                      onsubmit="return false;">

                                    <div class="dropdown">
                                        <button class="btn <?= $obj->isGoingClass ?> btn-block dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <?= $obj->isGoingText ?>
                                        </button>
                                        <div class="dropdown-menu btn-block" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item text-success p-3" href="javascript:void(0)" data-value="1">Going</a>
                                            <a class="dropdown-item text-danger p-3" href="javascript:void(0)" data-value="0">Not Going</a>
                                        </div>
                                    </div>

                                    <input type="hidden" name="uid" value="<?= $obj->gameUid ?>">
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
                                            form.find('.isGoing').val( $(this).data('value'));
                                            form.find('.btn-submit').trigger('click');
                                        });
                                    });
                                </script>

                            <?php endif; ?>

                        <?php else:?>
                            <button class="btn btn-primary disabled text-center btn-block">
                                Game Passed
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    <?php endforeach; ?>

<script>

    // scroll down to current week

    $(document).ready(function(){
		var currentGame = $('.current-game').first();
		var week = currentGame.find('.week');
		var offset = currentGame.offset();
		week.text(week.text() + ' (Next Game)');
		$('body, html').scrollTop( offset.top - 20 );
    });
</script>

<script>

    // load scores and place

    $(document).ready(function () {

    	var teams = [];

    	var getTeamClass = function(strTeam){
			return strTeam.toLowerCase().replace(/[^a-z0-9]/gmi, '');
        };

    	var tpl = `
            <div class='container d-none team-{team} stats'>
                <div class='row'>
                    <div class='col-12'>
                        <div class='card'>
                            <div class='card-header'>
                                Team Stats ({teamName})
                            </div>
                            <div class='card-body'>
                               <table class='table table-responsive'>
                                    <tr>
                                        <th>Place</th>
                                        <th>Team</th>
                                        <th>GP</th>
                                        <th>W</th>
                                        <th>L</th>
                                        <th>T</th>
                                        <th>GF</th>
                                        <th>GA</th>
                                        <th>GD</th>
                                        <th>PTS</th>
                                        <th>Team Rep</th>
                                    </tr>
                                    <tr>
                                        <td class='place'></td>
                                        <td class='team'></td>
                                        <td class='gp'></td>
                                        <td class='w'></td>
                                        <td class='l'></td>
                                        <td class='t'></td>
                                        <td class='gf'></td>
                                        <td class='ga'></td>
                                        <td class='gd'></td>
                                        <td class='pts'></td>
                                        <td class='teamRep'></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        `;

    	var renderTeamStats = function(){
    	    teams.forEach(function(obj){
				var localTemplate = tpl;
    	    	var teamClass = getTeamClass(obj.team);
				localTemplate = localTemplate.replace(/{team}/gmi, teamClass);
				localTemplate = localTemplate.replace(/{teamName}/gmi, obj.team);
                var t = $(localTemplate);
                for(key in obj){
					t.find('.' + key).html( obj[key] );
                }
				$('body').append(t);
            });
        };

    	var bindClickAndHover = function(theClass){
            $('.' + theClass).bind('click', function(e){
            	e.preventDefault();
            	e.stopPropagation();
				// team stats
                if( $('.team-' + theClass).hasClass('show-team')){
					$('.team-' + theClass).addClass('d-none').removeClass('show-team');
				}else{
                    $('.stats').removeClass('show-team').addClass('d-none');
					$('.team-' + theClass).addClass('show-team').removeClass('d-none');
				}
			});
        };

		$('body').click(function(e){
//			e.preventDefault();
//			e.stopPropagation();
			if($(e.target).hasClass('show-stats')){
				var isOpen = false;
				var which = null;
				$('.stats').each(function(){
					if( $(this).hasClass('show-team')){
						isOpen = true;
						which = $(this);
					}
				});
				if(isOpen){
					$('.stats').removeClass('show-team').addClass('d-none');
				}else{
					$('.stats').removeClass('show-team').addClass('d-none');
					which.addClass('show-team').removeClass('d-none');
				}
            }else{
				$('.stats').removeClass('show-team').addClass('d-none');
            }

		});

    	var getPlaces = function(html){

    		var masterTables = html.find('.rgMasterTable');
    		var table = $(masterTables[0]);
    		var rows = table.find('tr');
    		rows.each(function(idx, row){
                var r = $(row);
                var tdList = r.children();

                var obj = {
                    place: $(tdList[0]).text(),
                    team: $(tdList[1]).text(),
                    gp: $(tdList[2]).text(),
                    w: $(tdList[3]).text(),
                    l: $(tdList[4]).text(),
                    t: $(tdList[5]).text(),
					gf: $(tdList[6]).text(),
					ga: $(tdList[7]).text(),
					gd: $(tdList[8]).text(),
					pts: $(tdList[9]).text(),
					teamRep: $(tdList[10]).html()
                };

                teams.push(obj);

                var team = tdList[1];
                var teamClass = getTeamClass($(team).text());
                var teamEl = $('.' + teamClass);
                var place = idx; // first row is heading, that's why 1st place is index 1
                if(idx === 1){
                	place += 'st';
                }else if(idx === 2){
                	place += 'nd';
                }else if(idx === 3){
                	place += 'rd';
                }else if(idx >= 4){
                	place += 'th';
                }
                teamEl.siblings('.place').text('(' + place + ')');

                bindClickAndHover(teamClass);

            });

    		renderTeamStats();

        };

    	var getScores = function(html){
			var groups = html.find('.rgGroupHeader');

			groups.each(function(){

				var week = $(this);
				var rows = $(this).nextUntil('.rgGroupHeader');

				rows.each(function(){

					var thisRow = $(this);
					var tdList = $(this).children();
					var correctRow = false;

					tdList.each(function(){
						if( $(this).text().toLowerCase().indexOf('tyros') !== -1){
							correctRow = thisRow;
						}
					});

					if(correctRow !== false){

						try{

							var homeScore = '';
							var awayScore = '';

							var weekSelector = week.text().toLowerCase().replace(' ', '-').trim();
							var children = $(tdList[2]).children();

							children.each(function(idx, el){
								if(idx === 2){
									homeScore = $(el).text();
								}
								if(idx === 5){
									awayScore = $(el).text();
								}
							});

							var sel = $('.' + weekSelector); //.parent().next();

							if(homeScore !== '' && awayScore !== ''){
								var h = sel.find('.home');
								var a = sel.find('.away');

                                var hClass = '';
                                var aClass = '';

                                if(homeScore > awayScore){
                                    hClass = 'text-success';
                                    aClass = 'text-danger'
                                }else if(homeScore === awayScore){
                                    hClass = 'text-info';
                                    aClass = 'text-info';
                                }else{
                                    hClass = 'text-danger';
                                    aClass = 'text-success';
                                }
                                h.find('.score').text(homeScore).addClass(hClass);
                                a.find('.score').text(awayScore).addClass(aClass);
							}

						} catch(e){
                            console.error('e', e.message);
						}

					}

				});
			});
        };

		 $.ajax({
			url: '/schedule/remote',
			method: 'post',
			dataType: 'json',
			data: {
				isAjax: 1
            }
		}).done(function(res){

			var html = $(res.html);

			getScores(html);
			getPlaces(html);

         });

	});
</script>
