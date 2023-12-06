<?php defined('ABSPATH') or die('No script kiddies please!'); ?>


<div id="<?= $id; ?>" style="margin-bottom: 50px;">
    <p class="omen-title">
        <strong><?= "فال حافظ روزانه متولدین $monthName"; ?></strong>
    </p>

    <div class="omen-content">
        <p class="omen-content__title">
            <strong><?= $omenObj['anchor_text'] ?></strong>
        </p>

        <div class="omen-content__ghazal">
            <?php
            $split = explode("\n", $omenObj['ghazal']);
            ?>
            <p><?= $split[0]; ?></p>
            <p><?= $split[1]; ?></p>
            <br>
            <p><?= $split[2]; ?></p>
            <p><?= $split[3]; ?></p>
            <p>...</p>
        </div>

        <div class="omen-content__text">
            <p>
                <strong><?= $monthName; ?> ماهی عزیز...</strong>
                <?= $omenObj['omen'] ?>
            </p>
        </div>
    </div>

    <div class="omen-audio">
        <audio controls>
            <source src="<?= $omenObj['audio_url'] ?>" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
    </div>

    <div class="omen-anchor">
        <p>برای مطالعه جزئیات بیشتر در مورد این غزل به لینک زیر مراجعه نمایید.</p>
        <a href="<?= urldecode(str_replace('%', '', $omenObj['url'])) ?>">
            <?= $omenObj['anchor_text'] ?>
            <img src="<?= DAILYOMENS_SITE_STATIC . 'images/daily_hafez_omen/external-link.png' ?>" alt="external-link" width="18">
        </a>
    </div>
</div>