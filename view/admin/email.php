<?php
$__app = $GLOBALS['app'];


?>
<script>

    $(document).ready(function(){

		var formAdminEmailDoOnSuccess = function(){
			console.log('formAdminEmailDoOnSuccess');
        };

		var oForm = {
			formId: 'form-admin-email',
			doOnSuccess: formAdminEmailDoOnSuccess
		};

		var f = new JVForm(oForm);

    });



</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h1 class="card-header">
                    Admin > Email
                </h1>
                <div class="card-body">

                    <div class="alert alert-info">
                        <div class="mb-3">
                            When sending an email the Subject and Text Message fields are required.
                        </div>
                        <div class="mb-3">
                            If you want to send an HTML-based email you must enter all the HTML into the HTML field.
                            If you leave this field blank, the Text Message will be used in place of the HTML message
                            upon sending, and all carriage returns will be replaced with an HTML break.
                        </div>
                        <div class="mb-3">
                            If you have questions please contact Joe.
                        </div>
                    </div>

                    <form action="/admin/email/send" method="post" id="form-admin-email" data-type="json" onsubmit="return false;">

                        <div class="form-group">
                            <label for="subject">
                                Subject
                            </label>
                            <input id="subject" type="text" class="form-control" name="subject" value="">
                        </div>

                        <div class="form-group">
                            <label for="textMessage">
                                Text Message
                                <span class="text-danger">
                                    Required
                                </span>
                            </label>
                            <textarea class="form-control" name="textMessage" id="textMessage"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="htmlMessage">
                                HTML Message
                                <span class="d-block text-muted">
                                    Optional
                                </span>
                            </label>
                            <textarea class="form-control" name="htmlMessage" id="htmlMessage"></textarea>
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

