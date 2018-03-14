function Lineup(obj){
	
	this.formation = obj.formation || '1x1x3x4x2';
	this.attendance = obj.attendance;
	this.starters = obj.starters || [];
	this.subs = obj.subs || [];
	
	this.init = function(){
		
		// console.log('Lineup init', this);
		
		this.setSubs();
		this.setStarters();
		//
		this.removeStartersIfNotInAttendance();
		this.removeSubsIfNotInAttendance();
		this.removeStartersFromSubs();
		this.removeDuplicates();
		
	};
	
	this.setSubs = function(){
		// console.log('setSubs');
		var self = this;
		self.subs = [];
		this.attendance.forEach(function(oPlayer){
			self.subs.push({
				id: oPlayer.id,
				firstname: oPlayer.firstname,
				lastname: oPlayer.lastname,
				gender: oPlayer.gender,
				isgoing: null
			});
		});
		// console.log('subs', this.subs);
	};
	
	this.setStarters = function(){
		// testing as if this was saved data
		var self = this;
		
		// USE THIS
		if(this.starters.length < 11){
			for(var i=0; i<11; i++){
				if(typeof self.starters[i] === 'undefined' || self.starters[i].id === ''){
					self.starters[i] = {
						id: '',
						firstname: 'Nobody',
						lastname: 'Here',
						gender: '',
						isgoing: null
					}
				}
			}
		}
		
		// console.log('setStarters after', this.starters);
	};
	
	this.removeStartersIfNotInAttendance = function(){
		var self = this;
		this.starters.forEach(function(starter,starterIdx){
			
			var arrStarterExistsInAttendance = self.attendance.filter(function(att,attIdx){
				return starter.id === att.id;
			});
			
			if(!arrStarterExistsInAttendance.length){
				self.starters[starterIdx] = {
					id: '',
					firstname: '[Position Available]',
					lastname: '',
					gender: '',
					isgoing: null
				};
			}
		});
	};
	
	this.removeSubsIfNotInAttendance = function(){
		var self = this;
		var arrItemsToRemove = [];
		this.subs.forEach(function(sub, subIdx){
			var arrSubExistsInAttendance = self.attendance.filter(function(att,attIdx){
				return sub.id === att.id;
			});
			if(!arrSubExistsInAttendance.length){
				arrItemsToRemove.push(subIdx);
			}
		});
		
		arrItemsToRemove = arrItemsToRemove.reverse();
		for(var i=0; i<arrItemsToRemove.length;i++){
			this.subs.splice( arrItemsToRemove[i], 1);
		}
	};
	
	this.removeStartersFromSubs = function(){
		var self = this;
		var arrItemsToRemove = [];
		this.subs.forEach(function(sub,subIdx){
			var arrStarterFound = self.starters.filter(function(starter,starterIdx){
				return sub.id === starter.id;
			});
			if(arrStarterFound.length){
				arrItemsToRemove.push(subIdx);
			}
		});
		arrItemsToRemove = arrItemsToRemove.reverse();
		for(var i=0; i<arrItemsToRemove.length;i++){
			this.subs.splice( arrItemsToRemove[i], 1);
		}
	};
	
	this.removeDuplicates = function(){
		var self = this;
		var arrItemsToRemove = [];
		this.subs.forEach(function(sub, subIdx){
			var arrSubExistsInSubs = self.subs.filter(function(sub2,subIdx2){
				if(sub.id === sub2.id && subIdx !== subIdx2){
					arrItemsToRemove.push(subIdx2);
				}
				return sub.id === sub2.id && subIdx !== subIdx2;
			});
			// if(arrSubExistsInSubs.length){
			// 	arrItemsToRemove.push(subIdx);
			// }
		});
		arrItemsToRemove = arrItemsToRemove.reverse();
		for(var i=0; i<arrItemsToRemove.length;i++){
			this.subs.splice( arrItemsToRemove[i], 1);
		}
	};
	
	// this.init();
	
}