function Formations(){
	
	var self = this;
	
	this.formations = [
		'1x1x3x3x3',
		'1x1x3x4x2'
	];
	
	this.getFormations = function(){
		return this.formations;
	};
	
	return {
		getFormations: self.getFormations
	}
	
}