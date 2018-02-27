
<script>
	$(document).ready(function(){

		var $btnToLogin = $('#btn-toLogin');
		$btnToLogin.hide();


		var handleSuccess = function(){
			$btnToLogin.show();
        };

		var oForm = {
			formId: 'form-login',
			doOnSuccess: handleSuccess
		};

		var f = new JVForm(oForm);

	});
</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h1 class="card-header">
                    Forgot Password
                </h1>
                <div class="card-body">

                    <form id="form-login" action="/forgot-password/doForgotPassword" method="post" data-type="json" onsubmit="return false;">

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" id="email" />
                        </div>

                        <button class="btn btn-primary" id="btn-submit">
                            Submit
                        </button>

                        <a class="btn btn-secondary" href="/login" id="btn-toLogin">
                            Return to Login
                        </a>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>