(function ($) {
    $(window).on("load", function () { /*...*/ });
})(jQuery);
jQuery(document).ready(function ($) {
    "use strict"

    const submitBtn = document.getElementById('coffe_omen_submit');
    submitBtn.disabled = false;

    window.jq = $;
});


function showCoffeeOmen(e) {
    e.preventDefault();

    const nonce = document.forms['coffe_omen_frm']['coffe_omen_nonce'].value;
    const name = document.forms['coffe_omen_frm']['coffe_omen_name'].value;
    const thisBtn = document.forms['coffe_omen_frm']['coffe_omen_submit'];
    const errorLabel = document.getElementById('invalid_name_feedback');

    jq(errorLabel).fadeOut(200);
    const regex = /[A-Za-z0-9]/g;
    if (regex.test(name) || name.trim() === '') {
        jq(errorLabel).fadeIn(600).find('span').html('لطفا نام خود را فارسی تایپ کنید!').end();
        return;
    }

    const omenSentence = document.querySelector('.coffee-horoscope-content__body');
    jq.ajax({
        url: daily_omens_wp_ajax.AJAXURL,
        type: 'POST',
        data: {
            SECURITY: daily_omens_wp_ajax.SECURITY,
            action: 'showCoffeeOmen',
            nonce,
            name
        },
        beforeSend: () => {
            jq(omenSentence).fadeOut(200);
            thisBtn.disabled = true;
        },
        success: (res, xhr) => {
            if (xhr) {
                const sanitizedName = res.data.name;
                const sentence = res.data.omen;
                jq(omenSentence).fadeIn(600).html(`<p><strong>${sanitizedName} عزیز...</strong>${sentence}</p>`);
            }
        },
        error: (jqXHR, textStatus, errorThrown) => {
            alert('خطای نامشخص! لطفا مجددا تلاش کنید');
            console.log(errorThrown);
        },
        complete: () => {
            thisBtn.disabled = false;
        },
        timeout: daily_omens_wp_ajax.REQUEST_TIMEOUT
    });


}