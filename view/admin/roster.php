<?php $__roster = $GLOBALS['app']->rc->roster; ?>


<script>

</script>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h1 class="card-header">
                    Admin > Roster
                </h1>
                <div class="card-body">

                    <div class="row">
                        <?php foreach($__roster as $key => $obj): ?>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 text-center mb-5">

                                <h4><?= $obj->firstname ?></h4>
                                <h4><?= $obj->lastname ?></h4>

                                <div class="row">
                                    <div class="col-12 col-md-6 mb-3">
                                        <a href="/admin/roster/sms/<?= $obj->id ?>" class="btn btn-primary btn-block">
                                            SMS
                                        </a>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <a href="/admin/roster/email/<?= $obj->id ?>" class="btn btn-primary btn-block">
                                            Email
                                        </a>
                                    </div>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

