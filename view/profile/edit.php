<?php
    $__profile = $GLOBALS['app']->rc->results[0];
?>

<script>

    $(document).ready(function(){

    	// profile

		var oForm = {
			formId: 'form-edit-profile'
		};

		var f = new JVForm(oForm);

		// password

        var oPasswordForm = {
        	formId: 'form-edit-password',
            doOnSuccess: function(){
        		$('#currentPassword').val('').blur();
        		$('#newPassword').val('').blur();
        		$('#confirmNewPassword').val('').blur();
            }
        };

        var p = new JVForm(oPasswordForm);

    });
</script>

<div class="container-fluid mb-5">
    <div class="row">

        <div class="col-12 col-md-6 mb-5">
            <div class="card">
                <h1 class="card-header">
                    Edit Profile
                </h1>
                <div class="card-body">

                    <form action="/profile/update" method="post" data-type="json" id='form-edit-profile' onsubmit="return false">

                        <div class="row">
                            <div class="col-12">

                                <div class="form-group">
                                    <label>First Name</label>
                                    <input type="text" class="form-control" name="firstname" id="firstname" value="<?= $__profile->firstname ?>" />
                                </div>

                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control" name="lastname" id="lastname" value="<?= $__profile->lastname ?>" />
                                </div>

                                <div class="form-group">
                                    <label>
                                        Phone
                                        <span class="d-block text-warning">Required to receive SMS (text) messages.</span>
                                    </label>
                                    <input type="text" class="form-control" name="phone" id="phone" value="<?= $__profile->phone ?>" />
                                </div>

                                <div class="form-group">
                                    <label>
                                        Email
                                        <span class="d-block text-warning">Required to log into the site, and to receive email notifications.</span>
                                    </label>
                                    <input type="text" class="form-control" name="email" id="email" value="<?= $__profile->email ?>" />
                                </div>

                            </div>

                            <?php
                            /* Florin doesn't want this on the site, removing
                            <div class="col-xs-12 col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label>Address 1</label>
                                    <input type="text" class="form-control" name="address1" id="address1" value="<?= $__profile->address1 ?>" />
                                </div>

                                <div class="form-group">
                                    <label>Address 2</label>
                                    <input type="text" class="form-control" name="address2" id="address2" value="<?= $__profile->address2 ?>" />
                                </div>

                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" class="form-control" name="city" id="city" value="<?= $__profile->city ?>" />
                                </div>

                                <div class="form-group">
                                    <label>State</label>
                                    <input type="text" class="form-control" name="state" id="state" value="<?= $__profile->state ?>" />
                                </div>

                                <div class="form-group">
                                    <label>Zip</label>
                                    <input type="text" class="form-control" name="zip" id="zip" value="<?= $__profile->zip ?>" />
                                </div>
                            </div>
                            */
                            ?>

                        </div>

                        <button class="btn btn-primary">
                            Submit
                        </button>

                    </form>

                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card">
                <h1 class="card-header">
                    Edit Password
                </h1>
                <div class="card-body">

                    <form action="/profile/updatePassword" method="post" data-type="json" id='form-edit-password' onsubmit="return false">

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Current Password</label>
                                    <input type="password" class="form-control" name="currentPassword" id="currentPassword" value="" />
                                </div>

                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="password" class="form-control" name="newPassword" id="newPassword" value="" />
                                </div>

                                <div class="form-group">
                                    <label>Confirm New Password</label>
                                    <input type="password" class="form-control" name="confirmNewPassword" id="confirmNewPassword" value="" />
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-primary">
                            Submit
                        </button>

                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

