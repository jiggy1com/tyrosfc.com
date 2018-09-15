<?php foreach($__roster as $key => $obj): ?>

    <?php

        $__isActiveClass = $obj->isActive == 1 ? 'btn-success' : 'btn-danger';
        $__isActiveText = $obj->isActive == 1 ? 'Active' : 'Inactive';

    ?>

    <div class="col-12 col-sm-6 col-md-4 col-lg-3 text-center mb-5">

        <div class="card p-3">
            <h4><?= $obj->firstname ?></h4>
            <h4><?= $obj->lastname ?></h4>

            <div class="row">
                <div class="col-12 mb-3">
                    <a href="/admin/roster/sms/<?= $obj->id ?>" class="btn btn-primary btn-block">
                        SMS
                    </a>
                    <a href="/admin/roster/email/<?= $obj->id ?>" class="btn btn-primary btn-block">
                        Email
                    </a>

                    <form id="form-set-isActive-<?= $obj->id ?>"
                          class="form-set-isActive pt-2"
                          action="/admin/roster/status/update"
                          method="post"
                          data-type="json"
                          onsubmit="return false;">
                        <div class="dropdown">
                            <button class="btn <?= $__isActiveClass ?> btn-block dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?= $__isActiveText ?>
                            </button>
                            <div class="dropdown-menu btn-block" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item text-success p-3" href="javascript:void(0)" data-value="1">Active</a>
                                <a class="dropdown-item text-danger p-3" href="javascript:void(0)" data-value="0">Inactive</a>
                            </div>
                        </div>
                        <input type="hidden" name="isAjax" value="1">
                        <input type="hidden" name="rosterId" value="<?= $obj->id ?>">
                        <input type="hidden" name="isActive" value="" class="isActive">
                        <button class="btn btn-primary btn-submit d-none">
                            Submit
                        </button>
                    </form>

                    <script>
						$(document).ready(function(){
							var formId = 'form-set-isActive-' + <?= $obj->id ?>;
							var form = $('#' + formId);
							var btn = form.find('.btn').first();

							var doOnSuccess = function(){

								var v = form.find('.isActive');

								btn.removeClass('btn-primary')
								.removeClass('btn-success')
								.removeClass('btn-danger')
								.removeClass('btn-warning');

								if(v.val() === '0'){
									btn.addClass('btn-danger');
									btn.text('Inactive');
								}else if(v.val() === '1'){
									btn.addClass('btn-success');
									btn.text('Active');
								}

							};

							var oForm = {
								formId: formId,
								doOnSuccess: doOnSuccess
							};

							var f = new JVForm(oForm);

							form.find('.dropdown-item').click(function(){
								console.log('click');
								form.find('.isActive').val( $(this).data('value'));
								form.find('.btn-submit').trigger('click');
							});
						});
                    </script>

                </div>
            </div>
        </div>

    </div>
<?php endforeach; ?>