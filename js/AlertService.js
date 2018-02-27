
// construct alertService
function AlertService(){

	// public
	this.element 			= '';
	this.notification 		= '';
	this.notificationClass 	= 'info';

	// private
	this.spinner 			= "<span class='fa fa-spin fa-spinner'></span>";
	this.template 			= "<div class='alert'></div>";
	this.closeObj 			= "<span class='fa fa-times pull-right'></span>";

	// allow configuration when creating new object
	for(var prop in arguments[0]){
		if(this.hasOwnProperty(prop)){
			this[prop] = arguments[0][prop];
		}
	}

}

// prototype methods

AlertService.prototype.showNotification = function(obj){
	if(obj !== undefined){
		if(obj.hasOwnProperty('notification')){
			this.setNotification(obj.notification);
		}
		if(obj.hasOwnProperty('notificationClass')){
			this.setNotificationClass(obj.notificationClass);
		}
		if(obj.hasOwnProperty('element')){
			this.element = obj.element;
		}
	}

	this.removeAlert()
		.addAlert()
		.removeClass('alert-success alert-danger alert-info alert-warning')
		.addClass('alert-' + this.notificationClass)
		.html(this.spinner + ' ' + this.notification + this.closeObj)
		.slideDown(500);
	this.setDestroy();
	return this;
};

AlertService.prototype.setCloseObj = function(obj){
	this.closeObj = obj;
	return this;
};

AlertService.prototype.addAlert = function(){
	$(this.element[0]).parent().append(this.getTemplate());
	return $(this.element[0]).parent().find('.alert');
};

AlertService.prototype.removeAlert = function(){
	$(this.element).parent().find('.alert').remove();
	return this;
};

AlertService.prototype.setDestroy = function(){
	var self = this;
	$(this.element).parent().find('.alert').find('.fa-times').click(function(){
		self.destroy();
	});
	return this;
};

AlertService.prototype.destroy = function(){
	$(this.element).parent().find('.alert').remove();
};

AlertService.prototype.getTemplate = function(){
	return this.template;
};

AlertService.prototype.setNotification = function(str){
	this.notification = str;
	return this;
};

AlertService.prototype.setNotificationClass = function(str){
	this.notificationClass = str;
	return this;
};

AlertService.prototype.getNotification = function(){
	return privates.notification;
};

AlertService.prototype.getNotificationClass = function(){
	return privates.notificationClass;
};

/* How to use it

Notification is appended to parent of the configured element

$('#element').click(function(){

	var element = $(this);
	var alertService = new AlertService();
		alertService.showNotification({
			element : element, // optional, required on initialization
			notification : 'some notification',
			notificationClass : 'success or danger or info or warning',
		});

	
	or


	var alertService = new AlertService({
		element : $(this)
	});
		alertService.showNotification({
			notification : 'some notification',
			notificationClass : 'success or danger or info or warning',
		});

});

// To-Do

- Extend configuration to determine if the alert should be appended to or inserted into the "element"
- Extend configuration to allow placement of the alert:
	- prepend parent element
	- append parent element
	- element (container)

- Rename notification and notificationClass to something shorter
- Update methods to reflect name change

- Make it a jQuery plugin (extend jQuery) -- maybe not; consider getting rid of the jQuery parts
and rewrite it in vanilla JS

// include these styles (IMPORTANT)
 
 .alert{
 display: none;
 margin: 0 0 30px 0;
 }
 .alert:empty{
 display: none;
 }
 .alert .fa-spin{
 display: none;
 }
 .alert-info .fa-times{
 display: none;
 }
 .alert-info .fa-spin{
 display: inline-block;
 }
 .alert .fa-times{
 cursor: pointer;
 }
 .alert-show{
 display: block !important;
 }


*/
