function Game(oConfig){
	
	if(!oConfig.elementId){
		console.error('You need to specify the element ID for this Game object');
	}
	if(!oConfig.attendance){
		console.error('You need to pass in: attendance');
	}
	if(!oConfig.totalLineups){
		console.error('You must specify the totalLineups in Game constructor');
	}
	if(!oConfig.lineups){
		console.error('You can specify the lineups in Game constructor');
	}
	
	
	this.config = {
		// attendance: oConfig.attendance,
		elementId: oConfig.elementId,
		attendance: oConfig.attendance,
		totalLineups: oConfig.totalLineups || 4,
		lineups: oConfig.lineups || [],
		$__isAdminView: oConfig.$__isAdminView,
		$__isPrintLayout: oConfig.$__isPrintLayout
	};
	
	this.elements = {
		game: null,
		lineups: null
	};
	
	this.current = {
		lineup: []
	};
	
	this.getEmptyStarter = function(){
		return {
			id: '',
			firstname: '[Position Available]',
			lastname: '',
			gender: '',
			isgoing: null
		};
	};
	
	this.init = function(duplicate){
		
		if(!duplicate){
			duplicate = false;
		}
		
		var self = this;
		
		// setup the element
		this.element = $('#' + this.config.elementId);
		this.elements.game = this.element;
		
		// setup lineups
		if(this.config.lineups.length === 0){
			console.warn('no lineups');
			for(var i=0; i<this.config.totalLineups;i++){
				var oLineup = {
					formation: '1x1x3x4x2',
					// formation: '1x1x2x1x1x2x1x2',
					attendance: this.config.attendance,
					starters: [],
					subs: []
				};
				var lineup = new Lineup(oLineup);
					lineup.init();
					
				this.config.lineups.push(lineup);
				this.current.lineup.push({
					subIdx: null,
					starterIdx: null
				});
			}
		}else{
			console.warn('has some lineups');
			this.config.lineups.forEach(function(lineup, lineupIdx){
				var oLineup = {
					formation: self.config.lineups[lineupIdx].formation,
					attendance: self.config.attendance,
					starters: self.config.lineups[lineupIdx].starters,
					subs: self.config.lineups[lineupIdx].subs
				};
				var newLineup = new Lineup(oLineup);
				newLineup.init();
				
				self.config.lineups[lineupIdx] = newLineup;
				self.current.lineup.push({
					subIdx: null,
					starterIdx: null
				});
			});
		}
		
		// add missing
		if(this.config.lineups.length < this.config.totalLineups){
			var missingConfigMax = this.config.totalLineups - this.config.lineups.length;
			for(var missingConfig=0; missingConfig < missingConfigMax; missingConfig++){
				this.config.lineups.push({
					formation: this.config.formation,
					attendance: this.config.attendance,
					starters: [],
					subs: []
				});
				this.current.lineup.push({
					subIdx: null,
					starterIdx: null
				});
			}
			this.init(true);
		}
		
		if(!duplicate){
			this.renderFrame();
			this.renderFormations();
			this.renderSubs();
			this.renderStarters();
		}
		
		return this;
	};
	
	this.setAttendance = function(arrAttendance){
		this.config.attendance = arrAttendance;
		// for(var i=this.config.attendance.length; i<11;i++){
		// 	console.log('adding attendance');
		// 	this.config.attendance.push({
		// 		id: '',
		// 		firstname: 'test',
		// 		lastname: 'test',
		// 		gender: '',
		// 		isgoing: null
		// 	});
		// }
		
		return this;
	};
	
	this.setLineups = function(arrLineups){
		
		var self = this;
		
		this.config.lineups.forEach(function(lineup, lineupIdx){
			self.config.lineups[lineupIdx].starters = arrLineups[lineupIdx].starters;
			self.config.lineups[lineupIdx].subs = arrLineups[lineupIdx].subs;
			self.config.lineups[lineupIdx].attendance = self.config.attendance;
		});
		
		if(arrLineups.length === 0){
		
		}
		
		return this;
	};
	
	this.renderFrame = function(){
		var self = this;
		var isPrintLayout = this.config.$__isPrintLayout;
		this.config.lineups.forEach(function(lineup, lineupIdx){
			var h = "";
			
			var printClass = lineupIdx !== 0 && lineupIdx % 4 === 0 ? 'print-break' : '';
			var starterCopy = isPrintLayout ? '' : '';
			
			h += "<div class='row lineup " + printClass + " '>";
				
				// only show total number of players when editing (saves room when printing)
				if(!isPrintLayout){
					h += "<div class='col-12'>"+self.config.attendance.length+" Players</div>";
				}
			
				h += "<div class='col-12 col-md-8'><h4>Lineup "+(lineupIdx+1)+" Starters (5 Men, 5 Woman, + Goalie)</h4>";
					h += "<div class='starters container-fluid'>";
					h += "</div>";
				h += "</div>";
				h += "<div class='col-12 col-md-4'>";
					
					// formation
					h += "<h4>Formation</h4>";
					h += "<div class='formation'>";
					h += "</div>";
						
					// subs
					h += "<h4>Subs</h4>";
					h += "<div class='container-fluid'>";
						h += "<div class='subs row'>";
						h += "</div>";
					h += "</div>";
				h += "</div>";
				
				// add clone button, but not when printing
				if(self.config.$__isAdminView && !isPrintLayout){
					h += "<div class='col-12'>";
						h+= "<button class='btn btn-info btn-clone' data-lineupidx='" + lineupIdx + "'>Clone Down</button>";
					h += "</div>";
				}
				
			h += "</div>";
			h += "<hr>";
			self.element.append(h);
		});
	};
	
	this.renderSubs = function(){
		// console.warn('renderSubs');
		var self = this;
		var lineupElements = $('.lineup');
		
		// sort subs list by gender first
		this.config.lineups.forEach(function(obj, lineupIdx){
			var subs = obj.subs;
			subs.sort(function(a,b){
				var keyA = a.gender + a.firstname;
				var keyB = b.gender + b.firstname;
				if(keyA < keyB) return -1;
				if(keyA > keyB) return 1;
				return 0;
			});
			self.config.lineups[lineupIdx].subs = subs;
		});
		
		// sort subs by lastname
		// this.config.lineups.forEach(function(obj, lineupIdx){
		// 	var subs = obj.subs;
		// 	subs.sort(function(a,b){
		// 		var keyA = a.firstname;
		// 		var keyB = b.firstname;
		// 		if(keyA < keyB) return -1;
		// 		if(keyA > keyB) return 1;
		// 		return 0;
		// 	});
		// 	self.config.lineups[lineupIdx].subs = subs;
		// });
		
		// build subs list
		this.config.lineups.forEach(function(arr,lineupIdx){
			
			// clear current subs
			$(lineupElements[lineupIdx]).find('.subs').html('');
			
			// build new subs list
			var subs='';
			var subTextClass='';
			arr.subs.forEach(function(sub,subIdx){
				subTextClass = sub.gender === 'M' ? 'text-male' : sub.gender === 'F' ? 'text-female' : '';
				subs += "<div class='col-6 pt-2 pb-2 mb-1 sub " + subTextClass + "' data-lineupidx='" + lineupIdx + "' data-subidx='"+subIdx+"'>";
				subs += /*(subIdx+1) + ') ' + */sub.firstname + ' ' + self.lastInitial(sub.lastname);
				subs += "</div>";
			});
			$(lineupElements[lineupIdx]).find('.subs').append(subs);
		});
		this.setupClicks();
	};
	
	this.renderStarters = function(){
		
		var self = this;
		var lineupElements = $('.lineup');
		
		// build lineups
		this.config.lineups.forEach(function(arr, lineupIdx){
			
			// clear current lineup
			$(lineupElements[lineupIdx]).find('.starters').html('');
			
			var formation = self.config.lineups[lineupIdx].formation.split('x').reverse();
			var h = '';
			var playerIdx = 0;
			var playerTextClass;
			var player;
			formation.forEach(function(cols,rowIdx){
				var iterator = parseInt(cols);
				h += "<div class='row'>";
				for(var i=0; i<iterator;i++){
					player = self.config.lineups[lineupIdx].starters[playerIdx];
					playerTextClass = player.gender === 'M' ? 'text-male' : player.gender === 'F' ? 'text-female' : '';
					h += "<div class='col text-center pt-2 pb-2 mb-1 starter " + playerTextClass + "' data-lineupidx='" + lineupIdx + "' data-starteridx='" + playerIdx + "'>";
					h += player.firstname + " " + self.lastInitial(player.lastname);
					h += "</div>";
					playerIdx++;
				}
				h+= "</div>";
			});
			$(lineupElements[lineupIdx]).find('.starters').append(h);
		});
		this.setupClicks();
	};
	
	this.renderFormations = function(){
		
		var self = this;

		var allFormations = {
			'1x4x4x2': '4-4-2',
			'1x1x3x4x2': 'Sweeper 4-4-2',
			'1x3x5x2': '3-5-2',
			'1x1x2x5x2': 'Sweeper 3-5-2',
			'1x4x3x3': '4-3-3',
			'1x1x3x3x3': 'Sweeper 4-3-3'
		};
		
		var f = $('.formation');
		f.each(function(lineupIdx){
			var formation = self.config.lineups[lineupIdx].formation;
			var hSelect = "<div class='form-group'>";
				hSelect += "<select class='form-control select-formation' data-lineupidx='" + lineupIdx + "'>";
				for(var key in allFormations){
					selected = false;
					selectedText = '';
					if(key === formation){
						selected = true;
						selectedText = 'selected'
					}
					if(self.config.$__isAdminView || (!self.config.$__isAdminView && key === formation)){
						hSelect += "<option value='" + key + "'" + (key === formation ? ' selected' : '') + ">"+ allFormations[key] + "</option>";
					}
				}
				hSelect += "</select>";
			hSelect += "</div>";
			$(this).html(hSelect);
		});
	};
	
	this.setupClicks = function(){
		var self = this;
		if(this.config.$__isAdminView){
			var sub = $('.sub');
			var starter = $('.starter');
			var btnClone = $('.btn-clone');
			var btnFormation = $('.select-formation');
			
			// remove click events
			sub.off('click');
			starter.off('click');
			btnClone.off('click');
			btnFormation.off('change');
			
			// set click events
			sub.click(function(){
				self.handleClick($(this));
			});
			starter.click(function(){
				self.handleClick($(this));
			});
			starter.dblclick(function(){
				self.handleDblClick($(this));
			});
			
			btnClone.click(function(){
				self.handleBtnClone($(this));
			});
			
			btnFormation.change(function(){
				self.handleFormationChange($(this));
			});
			
			this.saveState();
		}
	};
	
	this.handleClick = function(el){
	
		// lineup
		var lineupIdx = el.data('lineupidx');
		var lineup = this.current.lineup[lineupIdx];
		
		// elements
		var elLineup = $('.lineup');
		var subs = $(elLineup[lineupIdx]).find('.sub');
		var starters = $(elLineup[lineupIdx]).find('.starter');
		
		// selected items
		var subIdx = el.data('subidx');
		var starterIdx = el.data('starteridx');
		
		// previously selected items
		var currentSubIdx = this.current.lineup[lineupIdx].subIdx;
		var currentStarterIdx = this.current.lineup[lineupIdx].starterIdx;
		
		if(typeof subIdx !== 'undefined'){
			
			if(currentSubIdx === null){
				lineup.subIdx = subIdx;
				el.addClass('bg-primary');
			}

			if(currentSubIdx !== null && subIdx !== currentSubIdx){
				lineup.subIdx = subIdx;
				subs.removeClass('bg-primary');
				el.addClass('bg-primary');
			}

			if(currentSubIdx !== null && subIdx === currentSubIdx){
				lineup.subIdx = null;
				subs.removeClass('bg-primary');
			}
			
			if(currentStarterIdx !== null){
				// console.warn('swap starter and sub (sub)');
				this.toggleStarterWithSub(lineupIdx, currentStarterIdx, subIdx);
			}

		}
		
		if(typeof starterIdx !== 'undefined'){
			
			// activate starter
			if(currentStarterIdx === null){
				// console.warn('activate starter');
				lineup.starterIdx = starterIdx;
				el.addClass('bg-primary');
			}
			
			// deactivate
			if(currentStarterIdx !== null && starterIdx === currentStarterIdx){
				// console.warn('deactivate starter');
				lineup.starterIdx = null;
				starters.removeClass('bg-primary');
			}
			
			// swap starters
			if(currentStarterIdx !== null && starterIdx !== currentStarterIdx){
				// console.warn('swap starters');
				lineup.starterIdx = null;
				starters.removeClass('bg-primary');
				
				var oFrom = this.config.lineups[lineupIdx].starters[starterIdx];
				var oTo = this.config.lineups[lineupIdx].starters[currentStarterIdx];
				
				var from = {
					id: oFrom.id,
					firstname: oFrom.firstname,
					lastname: oFrom.lastname,
					gender: oFrom.gender
				};
				
				var to = {
					id: oTo.id,
					firstname: oTo.firstname,
					lastname: oTo.lastname,
					gender: oTo.gender
				};
				
				this.config.lineups[lineupIdx].starters[starterIdx] = {
					id: to.id,
					firstname: to.firstname,
					lastname: to.lastname,
					gender: to.gender
				};
				
				this.config.lineups[lineupIdx].starters[currentStarterIdx] = {
					id: from.id,
					firstname: from.firstname,
					lastname: from.lastname,
					gender: from.gender
				};
				
				this.renderStarters();
				
			}
			
			if(currentSubIdx !== null){
				// console.warn('swap starter and sub');
				this.toggleStarterWithSub(lineupIdx, starterIdx, currentSubIdx);
			}
			
			// // check for toggle
			// currentSubIdx = this.current.lineup[lineupIdx].subIdx;
			// currentStarterIdx = this.current.lineup[lineupIdx].starterIdx;
			// if(currentStarterIdx !== null && currentSubIdx !== null){
			// 	this.toggleStarterWithSub(lineupIdx, currentStarterIdx, currentSubIdx);
			// }
			
			
			
		}
		
	};
	
	this.handleDblClick = function(el){
		// console.log('move to sub');
		var lineupIdx = el.data('lineupidx');
		var starterIdx = el.data('starteridx');
		var curStarter = this.config.lineups[lineupIdx].starters[starterIdx];
		if(curStarter.id !== ''){
			var newSub = {
				id: curStarter.id,
				firstname: curStarter.firstname,
				lastname: curStarter.lastname,
				gender: curStarter.gender
			};
			this.config.lineups[lineupIdx].starters[starterIdx] = this.getEmptyStarter();
			
			// only move the sub to the subs list if the sub is a real player
			if(newSub.id !== ''){
				this.config.lineups[lineupIdx].subs.push(newSub);
			}
			
			// console.log('lineupIdx', lineupIdx);
			this.render();
		}
	};
	
	this.handleBtnClone = function(el){
		var lineupIdx = el.data('lineupidx');
		var o = this.config.lineups[lineupIdx];
		var newStarters = [];
		var newSubs = [];
		o.starters.forEach(function(starter, starterIdx){
			var newStarter = {
				id: starter.id,
				firstname: starter.firstname,
				lastname: starter.lastname,
				gender: starter.gender,
				isgoing: starter.isgoing
			};
			newStarters.push(newStarter);
		});
		o.subs.forEach(function(sub,subIdx){
			var newSub = {
				id: sub.id,
				firstname: sub.firstname,
				lastname: sub.lastname,
				gender: sub.gender,
				isgoing: sub.isgoing
			};
			newSubs.push(newSub);
		});
		this.config.lineups[lineupIdx+1].starters = newStarters;
		this.config.lineups[lineupIdx+1].subs = newSubs;
		this.config.lineups[lineupIdx+1].formation = this.config.lineups[lineupIdx].formation;
		this.saveState();
		this.render();
	};
	
	this.saveState = function(){
		$('#game').val( JSON.stringify(this.config) );
	};
	
	this.toggleStarterWithSub = function(lineupIdx, starterIdx, subIdx){
		
		var starter = this.config.lineups[lineupIdx].starters[starterIdx];
		var sub = this.config.lineups[lineupIdx].subs[subIdx];
		
		var newStarter = {
			id: sub.id,
			firstname: sub.firstname,
			lastname: sub.lastname,
			gender: sub.gender
		};
		
		var newSub = {
			id: starter.id,
			firstname: starter.firstname,
			lastname: starter.lastname,
			gender: starter.gender
		};
		
		// move new starter
		this.config.lineups[lineupIdx].starters[starterIdx] = newStarter;
		
		
		if(newSub.id !== ''){
			// only move the newSub to the subs list if the sub is a real player
			this.config.lineups[lineupIdx].subs[subIdx] = newSub;
		}else{
			// else remove the sub from the list
			this.config.lineups[lineupIdx].subs.splice(subIdx,1);
		}
		
		this.current.lineup[lineupIdx].starterIdx = null;
		this.current.lineup[lineupIdx].subIdx = null;
		
		this.renderSubs();
		this.renderStarters();
	};
	
	this.handleFormationChange = function(el){
		var lineupIdx = el.data('lineupidx');
		this.config.lineups[lineupIdx].formation = el.val();
		this.render();
	};
	
	this.render = function(){
		this.renderFormations();
		this.renderSubs();
		this.renderStarters();
	};
	
	// this.init();
	
	// helpers
	this.lastInitial = function(lastName){
		return lastName.charAt(0).toUpperCase() + '.';
	}
	
};