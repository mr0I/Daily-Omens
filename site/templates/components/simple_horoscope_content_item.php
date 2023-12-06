<?php defined('ABSPATH') or die('No script kiddies please!'); ?>

<div id="<?= $id; ?>">
    <p class="month-title">
        <img src="<?= DAILYOMENS_SITE_STATIC . "images/daily_simple_omen/month_gifs/${gifName}.gif" ?>" alt="<?= 'فال روزانه ' . $monthName ?>">
        <strong><?= "فال روزانه متولدین $monthName"; ?></strong>
    </p>

    <div class="monthSpecTable">
        <table>
            <tbody>
                <tr>
                    <td style="border-top-right-radius: 6px;">
                        <span><?= __('How are you today?', 'daily_horoscope') ?></span>
                    </td>
                    <td style="font-size: 1.35em; border-top-left-radius: 6px;">
                        <?= $emojies[$numbersArray[$index]] ?? '---' ?>
                    </td>
                </tr>
                <tr>
                    <td><span><?= __('Your main feature', 'daily_horoscope') ?></span></td>
                    <td><?= $mainFeature; ?></td>
                </tr>
                <tr>
                    <td><span><?= __('Your planet', 'daily_horoscope') ?></span></td>
                    <td><?= $planet; ?></td>
                </tr>
                <tr>
                    <td><span><?= __('Birth month stone', 'daily_horoscope') ?></span></td>
                    <td><?= $stone; ?></td>
                </tr>
                <tr>
                    <td><span><?= __('Your favorite color', 'daily_horoscope') ?></td>
                    <td><?= $color; ?></td>
                </tr>
                <tr>
                    <td style="border-bottom-right-radius: 6px;"><span><?= __('Strengths', 'daily_horoscope') ?></td>
                    <td style="border-bottom-left-radius: 6px;"><?= $strengths; ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="month-omen-content">
        <p>
            <strong><?= $label; ?></strong>
            <?= $omen; ?>
        </p>
    </div>
</div>