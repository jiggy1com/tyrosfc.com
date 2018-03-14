
<script>
	$(document).ready(function(){

		var redirect = function(){
			window.location.href = '<?= $GLOBALS['app']->redirect ?>';
		};

		var oForm = {
			formId: 'form-login',
			doOnSuccess: redirect
		};

		var f = new JVForm(oForm);

	});
</script>

<div class="container-fluid">
    <div class="row justify-content-md-center">
        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-6 col-xl-6">
            <div class="card">
                <h1 class="card-header">
                    Login
                </h1>
                <div class="card-body">

                    <form id="form-login" action="/login/doLogin" method="post" data-type="json" onsubmit="return false;" > <!--  onsubmit="return doLogin();" -->

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" id="email" />
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" id="password" autocomplete="current-password" />
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="rememberMe" name="rememberMe">
                            <label class="form-check-label-off" for="rememberMe">
                                Remember Me
                            </label>
                        </div>

                        <div class="row mt-3">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <button class="btn btn-primary btn-block" id="btn-submit">
                                    Submit
                                </button>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                <a class="btn btn-secondary btn-block" href="/forgot-password">
                                    Forgot Password
                                </a>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

