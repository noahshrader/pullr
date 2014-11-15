<div id="collapseFour" class="<?= ($campaign->twitterEnable? 'twitterEnable ': '').($campaign->youtubeEnable? 'youtubeEnable ': '').($campaign->facebookEnable? 'facebookEnable ': '') ?> socialenable">
    <div class="social">
        <!-- Twitter -->
        <div class="module-inner cf">
            <h5><i class="icon mdib-twitter2"></i></h5>
            <?= $form->field($campaign, 'twitterEnable')->label(false)->checkbox([], false); ?>
            <?= $form->field($campaign, 'twitterName', ['autoPlaceholder' => false])->label("<b>Twitter Username</b> (e.g. getpullr)"); ?>
        </div>
        <!-- YouTube -->
        <div class="module-inner cf">
            <h5><i class="icon mdib-youtube2"></i></h5>
            <?= $form->field($campaign, 'youtubeEnable')->label(false)->checkbox([], false); ?>
            <?= $form->field($campaign, 'youtubeUrl', ['autoPlaceholder' => false])->label("<b>YouTube Channel</b> (e.g. https://www.youtube.com/user/getpullr)"); ?>
        </div>
        <!-- Facebook -->
        <div class="module-inner cf">
            <h5><i class="icon mdib-facebook"></i></h5>
            <?= $form->field($campaign, 'facebookEnable')->label(false)->checkbox([], false); ?>
            <?= $form->field($campaign, 'facebookUrl', ['autoPlaceholder' => false])->label("<b>Facebook URL</b> (e.g. https://www.facebook.com/getpullr)"); ?>
        </div>
    </div>
</div>