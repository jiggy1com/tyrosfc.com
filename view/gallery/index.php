<?php
$__list = $GLOBALS['app']->rc->galleryList;
?>

<div class="card">
    <h1 class="card-header">
        Gallery
    </h1>
    <div class="card-body">
        <ul class="list-group">
            <?php foreach($__list as $folder): ?>
                <li class="list-group-item">
                    <a href="/gallery/<?= $folder ?>">
                        <?= $folder ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

