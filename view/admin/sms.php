<?php
$__app = $GLOBALS['app'];


?>
<script>

	$(document).ready(function(){

		var formAdminSMSDoOnSuccess = function(){

			var errors = f.res.arrayOfErrors;
			errors.forEach(function(obj){
				var err = "<div class='alert alert-danger'>An error occurred sending to " + obj.firstname + " " + obj.lastname + " (" + obj.phone + "). The error was:<br> " + obj.error + "</div>";
				$('#' + f.formId).after(err);
            });

		};

		var oSMSForm = {
			formId: 'form-admin-sms',
			doOnSuccess: formAdminSMSDoOnSuccess
		};

		var f = new JVForm(oSMSForm);

	});



</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h1 class="card-header">
                    Admin > SMS
                </h1>
                <div class="card-body">

                    <div class="alert alert-info">
                        <div class="mb-3">
                            This form will send a text message to the roster,
                            assuming they have their phone number saved to their profile.
                        </div>
                        <div>
                            Please be patient as we iterate through the list of recipients.
                        </div>
                    </div>

                    <form action="/admin/sms/send" method="post" id="form-admin-sms" data-type="json" onsubmit="return false;">

                        <div class="form-group">
                            <label for="textMessage">
                                SMS Message
                                <span class="d-block text-danger">
                                    Required
                                </span>
                            </label>
                            <textarea class="form-control" name="smsMessage" id="smsMessage" maxlength="160"></textarea>
                            <span class="d-block text-muted float-right">
                                    Max 160 Characters
                                </span>
                        </div>

                        <div class="form-group">
                            <label for="sendTo">
                                <select name="sendTo" id="sendTo" class="form-control">
                                    <option value="1">Test to Joe Only</option>
                                    <option value="7">Test to Florin Only</option>
                                    <option value="activeRoster">Active Roster</option>
                                    <option value="inActiveRoster">Inactive Roster</option>
                                    <option value="fullRoster">Full Roster</option>
                                </select>
                            </label>
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

