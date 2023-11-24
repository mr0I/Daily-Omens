<?php defined('ABSPATH') or die('No script kiddies please!'); ?>

<section class="horoscope">
    <div class="horoscope-content">
        <h2 class="horoscope-content__title"><?= esc_html('Prophets Horoscope', 'daily_omens') ?></h2>
        <figure>
            <img src="<?= plugins_url('static/images/anbia_img.png', dirname(__FILE__)) ?>" alt="<?= esc_attr('Prophets Horoscope Picture', 'daily_omens') ?>">
        </figure>
        <p class="horoscope-content__aye">
            بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ ﴿۱﴾
            الْحَمْدُ للّهِ رَبِّ الْعَالَمِينَ ﴿۲﴾ الرَّحْمنِ الرَّحِيمِ ﴿۳﴾
            مَالِكِ يَوْمِ الدِّينِ ﴿۴﴾ إِيَّاكَ نَعْبُدُ وإِيَّاكَ نَسْتَعِينُ ﴿۵﴾
            اهدِنَا الصِّرَاطَ المُستَقِيمَ ﴿۶﴾
            صِرَاطَ الَّذِينَ أَنعَمتَ عَلَيهِمْ غَيرِ المَغضُوبِ عَلَيهِمْ وَلاَ الضَّالِّينَ ﴿۷﴾
        </p>
        <p class="horoscope-content__niat"><?= esc_html('Make an intention and click on the button', 'daily_omens') ?></p>
        <button class="horoscope-content__submitBtn" onclick="loadRandomHoroscope(event)">
            <?= esc_html('Prophets online horoscope', 'daily_omens') ?>
        </button>
        <input type="hidden" id="horoscope_nonce" value="<?= wp_create_nonce('horoscope-nonce') ?>">
    </div>
</section>

<section class="horoscope" id="horoscope_answer_section" style="display: none;"></section>



<script id='prophets_omen_script'>
    const PO_SITE_AJAX = {
        'AJAXURL': '<?= admin_url('admin-ajax.php'); ?>',
        'SECURITY': '<?= wp_create_nonce('Dnt3dUF8U4FRBNt3'); ?>',
        "REQUEST_TIMEOUT": "30000",
        'SUBMIT_BTN_TXT': '<?= esc_html('Prophets online horoscope', 'daily_omens') ?>',
        'WAITING_TXT': '<?= esc_html('Please Wait...', 'daily_omens') ?>',
        'PROPHET_PIC_SRC': '<?= MYPH_SITE_IMAGES . 'anbia/prophet_1.png' ?>',
        'daily_omens_PICTURE': '<?= esc_html('Prophets Horoscope Picture', 'daily_omens') ?>',
        'HOROSCOPE_RESULT': '<?= esc_html('Horoscope Result:', 'daily_omens') ?>',
        'LUCKY_DAY_FOR_YOU': '<?= esc_html('Lucky Day for you:', 'daily_omens') ?>',
        'HOROSCOPE_AGAIN': '<?= esc_html('Horoscope Again', 'daily_omens') ?>',
    };
</script>
<?php
$pluginVersion = boolval(DOMENS_IS_DEV) ? time() : (get_plugin_data(__FILE__, false))['Version'];
?>
<script src="<?= plugin_dir_url(__FILE__) . '../static/js_obfuscated/po_script.js?ver=' . $pluginVersion  ?>" defer></script>