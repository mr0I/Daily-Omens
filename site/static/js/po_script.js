jQuery(document).ready(function ($) {
    "use strict";
    window.jq = $;
});


function loadRandomHoroscope(event) {
    event.preventDefault();
    const nonce = document.getElementById('horoscope_nonce').value;
    const submitBtn = event.target || event.currentTarget;

    jq.ajax({
        url: MYPH_SITE_AJAX.AJAXURL,
        type: 'POST',
        data: {
            SECURITY: MYPH_SITE_AJAX.SECURITY,
            action: 'fetchHoroscopes',
            nonce: nonce
        },
        beforeSend: () => {
            jq(submitBtn).text(MYPH_SITE_AJAX.WAITING_TXT).attr('disabled', true);
        },
        success: (res, xhr) => {
            if (xhr) {
                const answerSection = document.getElementById('horoscope_answer_section');
                let luckyDayTextClass = '';
                switch (res.data.h_result) {
                    case 'عالی':
                        luckyDayTextClass = 'text-green';
                        break;
                    case 'خوب':
                        luckyDayTextClass = 'text-blue';
                        break;
                    case 'بد':
                        luckyDayTextClass = 'text-red';
                        break;
                    default:
                        break;
                }

                jq(answerSection).append(`
                    <div class="horoscope-content">
                        <figure>
                            <img src="${MYPH_SITE_AJAX.PROPHET_PIC_SRC}" alt="">
                        </figure>
                        <h3 class="horoscope-content__title" id="horoscope_answer_title">
                            ${res.data.h_name}
                        </h3>
                        <h4>${MYPH_SITE_AJAX.HOROSCOPE_RESULT}
                            <span class="text-green" style="font-weight: 600">${res.data.h_result}</span>
                        </h4>
                        ${(res.data.h_luckyday != '-' && res.data.h_luckyday != '')
                        ? `<h3>${MYPH_SITE_AJAX.LUCKY_DAY_FOR_YOU}<span class="text-default">&nbsp;${res.data.h_luckyday}</span></h3>`
                        : ''
                    }
                        <p class="horoscope-content__dobeity" style="margin-top:20px">${res.data.h_mesraOne}</p>
                        <p class="horoscope-content__dobeity">${res.data.h_mesraTwo}</p>
                        <p style="margin:0"><span class="text-green">${res.data.h_separator}</span></p>
                        <p class="horoscope-content__dobeity">${res.data.h_mesraThree}</p>
                        <p class="horoscope-content__dobeity">${res.data.h_mesraFour}</p>
                        <p class="horoscope-content__desc">${res.data.h_description}</p>
                        <button class="horoscope-content__submitBtn" onclick="horoscopeAgain(event)">
                            ${MYPH_SITE_AJAX.HOROSCOPE_AGAIN}
                        </button>
                    </div>
                `);

                setTimeout(() => {
                    jq('.horoscope').slideUp(400);
                    jq(answerSection).slideDown(200);
                }, 150);
            }
        },
        error: (jqXHR, textStatus, errorThrown) => {
            console.error(errorThrown);
        },
        complete: () => {
            jq(submitBtn).text(MYPH_SITE_AJAX.SUBMIT_BTN_TXT).attr('disabled', false);
        },
        timeout: MYPH_SITE_AJAX.REQUEST_TIMEOUT
    });
}

function horoscopeAgain(event) {
    event.preventDefault();
    const answerSection = document.getElementById('horoscope_answer_section');
    const horoscopeSection = jq('.horoscope');
    answerSection.innerHTML = '';
    jq(horoscopeSection).slideDown(300);
    jq(answerSection).slideUp(400);
    setTimeout(() => {
        jq(horoscopeSection).get(0).scrollIntoView({ behavior: 'smooth' });
    }, 500);
}