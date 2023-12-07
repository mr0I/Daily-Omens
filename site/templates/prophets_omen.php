<?php defined('ABSPATH') or die('No script kiddies please!');
// die('sadad');
?>

<section class="horoscope">
    <div class="horoscope-content">
        <h2 class="horoscope-content__title"><?= esc_html('Prophets Horoscope', 'daily_omens') ?></h2>
        <figure>
            <img src="<?= plugins_url('static/images/prophets_omen/anbia_img.png', dirname(__FILE__)) ?>" alt="<?= esc_attr('Prophets Horoscope Picture', 'daily_omens') ?>">
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