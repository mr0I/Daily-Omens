<?php defined('ABSPATH') or die('No script kiddies please!'); ?>


<section class="coffee-horoscope">
    <div class="coffee-horoscope-content">
        <div class="coffee-horoscope-content__header">
            <h1>فال قهوه با اسم آنلاین</h1>
            <figure>
                <img src="<?= DAILYOMENS_SITE_STATIC . '/images/coffee_omen/coffee.png' ?>" alt="فال قهوه با اسم آنلاین" width="100">
            </figure>
            <form action="" method="post" name="coffe_omen_frm" onsubmit="showCoffeeOmen(event)">
                <div class="frm-group">
                    <input type="text" name="coffe_omen_name" placeholder="اسم خود را وارد کنید...">
                    <div class="invalid-feedback" id="invalid_name_feedback">
                        <span></span>
                    </div>
                </div>
                <input type="hidden" name="coffe_omen_nonce" value="<?= wp_create_nonce('coffe-omen-nonce') ?>">
                <button type="submit" name="coffe_omen_submit" id="coffe_omen_submit" disabled></button>
            </form>
        </div>

        <div class="coffee-horoscope-content__body"></div>
    </div>
</section>