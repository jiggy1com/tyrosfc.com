// TODO: break all of thes JS files into separate files, and then bind to JV or Jiggy OR something

//
// ALERT
//

var JVAlert = function(obj){
	
	if(typeof obj.elementId === 'undefined'){
		console.error('Missing elementId in JVAlert constructor');
	}
	
	this.elementId = obj.elementId;
	this.alertClass = null;
	
	this.setSuccess = function(status){
		console.log('setSuccess', status);
		if(status === true){
			this.alertClass = 'alert-success';
		}else if(status === false){
			this.alertClass = 'alert-danger';
		}else if(status === 'info'){
			this.alertClass = 'alert-info';
		}else{
			// TODO: maybe add more??/
			this.alertClass = 'alert-warning';
		}
		return this;
	};
	
	this.showAlert = function(msg){
		console.log('showAlert', this.elementId, msg);
		$('#' + this.elementId).append("<div class='mt-3 alert " + this.alertClass +"'>" + msg + "</div>")
	};
	
	this.hideAlert = function(){
		console.log('hideAlert');
		$('#' + this.elementId).find('.alert').remove();
	};
	
	this.init = function(){
	
	};
	
	this.init();
	
	return {
		elementId: this.elementId,
		showAlert: this.showAlert,
		hideAlert: this.hideAlert,
		setSuccess: this.setSuccess
	}
	
};

//
// AJAX
//

var JVAjax = function(obj){
	
	this.data = null;
	
	this.setData = function(data){
		this.data = data;
	};
	
	this.submit = function(){
		
		return $.ajax({
			url: obj.url,
			action: obj.action,
			dataType: obj.dataType,
			data: this.data
		}).done(function(res){
			return res;
		});
		
	};
	
	return {
		submit: this.submit,
		setData: this.setData
	}
	
};

//
// Loading
//

var JVLoading = function(){
	
	this.isLoading = false;
	
	this.setLoading = function(isLoading){
		console.log('JVLoading', this);
		this.isLoading = isLoading;
		console.log('this', this);
		this.render();
	};
	
	this.render = function(){
		console.log('render', this.isLoading);
		if(this.isLoading){
			this.showLoading();
		}else{
			this.removeLoading();
		}
	};
	
	this.showLoading = function(){
		
		var body = $('body');
		
		var jvLoadingOverlayStyle = {
				backgroundColor: 'rgba(0, 0, 0, 0.5)',
				position: 'fixed',
				top: 0,
				right: 0,
				bottom: 0,
				left: 0
			};
		
		var jvLoadingGutsStyle = {
			zIndex: 1,
			position: 'fixed',
			top: '50%',
			left: '50%',
			transform: 'translate(-50%, -50%)',
			boxShadow: '0 0px 20px 5px #333',
			borderRadius: '10px',
			backgroundColor: '#fff',
			color: '#000',
			padding: '30px'
		};
		
		var overlayHtml = "<div class='jv-loading-overlay'><div class='jv-loading'><span class='fa fa-4x fa-spin fa-spinner'></span></div></div>";
		body.append(overlayHtml);
		body.find('.jv-loading-overlay').css(jvLoadingOverlayStyle).find('.jv-loading').css(jvLoadingGutsStyle);
		// .click(self.removeLoading)
		
	};
	
	this.removeLoading = function(){
		$('body').find('.jv-loading-overlay').remove();
	};
	
	return {
		setLoading: this.setLoading,
		render: this.render,
		showLoading: this.showLoading,
		removeLoading: this.removeLoading
	}
	
};

//
// FORM HANDLING
//

var JVForm = function(obj){
	
	if(typeof obj.formId === 'undefined'){
		console.error("Missing 'formId' in JVForm constructor argument.");
	}
	
	this.argument = obj;
	this.doOnSuccess = typeof obj.doOnSuccess !== 'undefined' ? obj.doOnSuccess : function(){
		console.warn('doOnSuccess was not passed into the JVForm constructor, so nothing happened after a successful form submission.');
	};
	
	this.form = $('#' + obj.formId);
	this.formId = obj.formId;
	this.btnSubmit = null;
	
	this.isLoading = false;
	this.res = null; // ajax response
	this.message = '';
	this.success = false;
	
	this.JVAjax = null; // new JVAjax({});
	this.JVLoading = null;
	this.JVAlert = null;
	
	// TODO: ???
	this.redirect = ''; // where to go next
	
	
	
	this.init = function(){
		
		var self = this;
		
		// notify developer of issues
		if( typeof $(this.form).attr('action') === 'undefined'){
			console.error("Missing attribute 'action' in form: ", this.argument.formId);
		}
		if( typeof $(this.form).attr('method') === 'undefined'){
			console.error("Missing attribute 'method' in form: ", this.argument.formId);
		}
		if( typeof $(this.form).attr('data-type') === 'undefined'){
			console.error("Missing attribute 'data-type' in form: ", this.argument.formId);
		}
		
		// set the ajax object
		this.JVAjax = new JVAjax({
			url: $(this.form).attr('action'),
			method: $(this.form).attr('method'),
			dataType: $(this.form).data('type'),
			data: null
		});
		
		// set the loading object
		this.JVLoading = new JVLoading();
		
		this.JVAlert = new JVAlert({
			elementId: this.formId
		});
		
		// set submit button
		this.btnSubmit = this.form.find('.btn-primary');
		
		// handle click of submit button
		this.btnSubmit.click(function(){
			
			self.JVLoading.setLoading(true);
			
			self.JVAlert.hideAlert();
			
			self.JVAjax.setData( $(self.form).serialize() );
			
			var clickSubmit = self.JVAjax.submit();
			
			clickSubmit.done(function(res){
				self.res = res;
				self.AjaxDone();
			});
		});
		
	};
	
	this.getMessage = function(){
		return this.message;
	};
	
	this.getSuccess = function(){
		return this.success;
	};
	
	this.AjaxDone = function(){
		console.log('AjaxDone, this.res:', this.res);
		this.JVAlert.setSuccess(this.res.success).showAlert(this.res.message);
		this.JVLoading.setLoading(false);
		
		if(this.res.success){
			this.doOnSuccess();
		}
		
	};
	
	this.init();
	
	return {
		submit: this.submit,
		getMessage: this.getMessage,
		getSuccess: this.getSuccess
	}
	
};