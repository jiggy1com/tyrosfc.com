<script>
	$(document).ready(function(){

		// form, on success
		var formUpdateAttendanceSelectedBtn = null;
		var formUpdateAttendanceDoOnSuccess = function(){
			console.log('formUpdateAttendanceDoOnSuccess');
			formUpdateAttendanceModal.find('.btn').each(function(){
				$(this).find('svg').remove();
			});
			formUpdateAttendanceSelectedBtn.find('span').before("<span class='fa fa-check mr-1'></span>");
		};

		// setup form
		var oFormUpdateAttendance = {
			formId: 'form-modal-change-attendance',
			doOnSuccess: formUpdateAttendanceDoOnSuccess
		};

		var formUpdateAttendance; // form var placeholder

		var formUpdateAttendanceModal = $('#modal-change-attendance');
		formUpdateAttendanceModal.find('.btn-block').click(function(){
			formUpdateAttendanceSelectedBtn = $(this);
			formUpdateAttendanceModal.find("input[name='isGoing']").val( $(this).data('value') );
			formUpdateAttendance = new JVForm(oFormUpdateAttendance);
			formUpdateAttendance.doSubmit();
		});

		// setup & update modal
		$('.btn-change-attendance').click(function(e){

			e.preventDefault();
			e.stopPropagation();

			var nearest = $(this).closest('.roster-user');

			var firstname = $(nearest).data('firstname');
			var lastname = $(nearest).data('lastname');
			var isGoing = $(nearest).data('isgoing');
			var rosterId = $(nearest).data('rosterid');

			formUpdateAttendanceModal.find('.btn-block').each(function(){
				console.log('data', $(this).data('value'), isGoing);
				$(this).find('svg').remove();
				if( $(this).data('value') === isGoing){
					$(this).find('span').before("<span class='fa fa-check mr-1'></span>");
                }
            });

			formUpdateAttendanceModal.find('form').attr('action', '/admin/schedule/game/<?= $__uid ?>/attendance/update');
			formUpdateAttendanceModal.find('.firstname').text(firstname);
			formUpdateAttendanceModal.find('.lastname').text(lastname);
			formUpdateAttendanceModal.find("input[name=rosterId]").val( rosterId );
			formUpdateAttendanceModal.find("input[name=isGoing]").val( isGoing );
			formUpdateAttendanceModal.modal({
				show: true
			});

		});

	});
</script>

<div id="modal-change-attendance" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Attendance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post" data-type="json" id="form-modal-change-attendance" onsubmit="return false;">

                    <input type="hidden" name="rosterId" value="" />
                    <input type="hidden" name="isGoing" value="" />

                    <h4>Update the attendance for

                        <span class="firstname text-info"></span>
                        <span class="lastname text-info"></span></h4>
                    <div class="row">
                        <div class="col-12 col-md-4 mb-3">
                            <button class="btn btn-success btn-block" data-value="1">
                                <span>Going</span>
                            </button>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <button class="btn btn-danger btn-block" data-value="0">
                                <span>Not Going</span>
                            </button>
                        </div>
                        <div class="col-12 col-md-4 mb-3">
                            <button class="btn btn-warning btn-block" data-value="">
                                <span>Unknown</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>