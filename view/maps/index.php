<?php
$__mapsList = $GLOBALS['app']->rc->mapsList;
?>

<div class="card">
    <h1 class="card-header">
        Maps
    </h1>
    <div class="card-body">

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <?php foreach($__mapsList as $key => $obj): ?>
                <?php
                $__tabClass = $key === 0 ? 'active' : '';
                ?>
                <li class="nav-item">
                    <a class="nav-link <?= $__tabClass ?>"
                       id="t<?= $key ?>-tab"
                       data-toggle="tab"
                       href="#t<?= $key ?>"
                       role="tab"
                       aria-controls="t<?= $key ?>"
                       aria-selected="true">
                        <?= $obj->locationName ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="tab-content" id="myTabContent">
            <?php foreach($__mapsList as $key => $obj): ?>
                <?php $__tabClass = $key === 0 ? 'show active' : ''; ?>
                <div class="tab-pane fade p-3 <?= $__tabClass ?>"
                     id="t<?= $key ?>"
                     role="tabpanel"
                     aria-labelledby="t<?= $key ?>-tab">

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-8">
                            <iframe
                                width="100%"
                                height="400"
                                frameborder="0"
                                style="border:0"
                                src="<?= $obj->iFrameSrc?>"
                                allowfullscreen>
                            </iframe>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4">

                            <h4><?= $obj->locationName ?></h4>
                            <h5><?= $obj->address ?></h5>

                            <a href="<?= $obj->mapLink ?>" class="btn btn-primary btn-block" target="_blank">
                                Directions
                            </a>

                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>


    </div>
</div>
