<?php
$__app = $GLOBALS['app'];
$__list = $__app->rc->galleryList;
$__folder = $__app->router->getParam('gallery');
?>

<div class="card">
    <h1 class="card-header">
        Gallery: <?= $__folder ?>
    </h1>
    <div class="card-body">

        <div class="row">
            <div class="col-12 pb-4">
                <a href="/gallery" class="btn btn-primary">
                    <span class="fa fa-chevron-left"></span>
                    Return To Gallery List
                </a>
            </div>
        </div>

        <div class="row lightbox">
            <?php foreach($__list as $file): ?>
                <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3 pb-4">
                    <?php
                    /*<img src="/img/gallery/<?= $__folder ?>/<?= $file ?>">*/
                    ?>
                    <img src="https://d1eciq71i8xwm3.cloudfront.net/fit-in/400x400/<?= $file ?>">
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>

<script>
    var l = new Lightbox();
    l.init();
</script>