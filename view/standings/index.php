<div class="container-fluid spinner-container">
    <div class="row">
        <div class="col-12 text-center">
            <h1>Loading Standings...</h1>
            <span class="fa fa-spin fa-spinner fa-4x"></span>
        </div>
    </div>
</div>

<script>
	$(document).ready(function () {

		var teams = [];

		var tpl = "" +
            "<tr>" +
                "<td class='place '></td>" +
                "<td class='team'></td>" +
                "<td class='gp'></td>" +
                "<td class='w'></td>" +
                "<td class='l'></td>" +
                "<td class='t'></td>" +
                "<td class='gf'></td>" +
                "<td class='ga'></td>" +
                "<td class='gd'></td>" +
                "<td class='pts'></td>" +
                "<td class='teamRep'></td>" +
			"</tr>";

		var tplWrapper = "" +
            "<div class='container-fluid'>" +
                "<div class='row'>" +
                    "<div class='col-12'>" +
                        "<div class='card'>" +
                            "<h1 class='card-header'>" +
                                "Standings" +
                            "</h1>" +
                            "<div class='card-body'>" +
                                "<table class='table table-responsive'>" +
                                    "<tr>" +
                                        "<th>Place</th>" +
                                        "<th>Team</th>" +
                                        "<th>GP</th>" +
                                        "<th>W</th>" +
                                        "<th>L</th>" +
                                        "<th>T</th>" +
                                        "<th>GF</th>" +
                                        "<th>GA</th>" +
                                        "<th>GD</th>" +
                                        "<th>PTS</th>" +
                                        "<th>Team Rep</th>" +
                                    "</tr>" +
                                "</table>" +
                            "</div>" +
                        "</div>" +
                    "</div>" +
                "</div>" +
            "</div>";

		var renderStandings = function(){

			var main = $('body').find('.mb-5').first();
			main.append(tplWrapper);

			var table = main.find('.card-body').find('table');

			teams.forEach(function(obj){
				var t = $(tpl);
				for(key in obj){
					t.find('.' + key).html( obj[key] );
				}
				table.append(t);
			});
		};

		var handleStandings = function(html){

			var masterTables = html.find('.rgMasterTable');
			var table = $(masterTables[0]);
			var rows = table.find('tr');
			rows.each(function(idx, row){

				if(idx > 0){
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
                }

			});

			renderStandings();

		};

		$.ajax({
			url: '/schedule/remote',
			method: 'post',
			dataType: 'json',
			data: {
				isAjax: 1
			}
		}).done(function(res){
			$('.spinner-container').remove();
			var html = $(res.html);
			handleStandings(html);
		});

	});
</script>