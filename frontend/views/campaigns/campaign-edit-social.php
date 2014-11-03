<div id="collapseFour" class="<?= ($campaign->twitterEnable? 'twitterEnable ': '').($campaign->youtubeEnable? 'youtubeEnable ': '').($campaign->facebookEnable? 'facebookEnable ': '') ?> socialenable">
	<!-- Twitter -->
    <div class="module-inner">
        <div class="social cf">
            <h5><i class="icon icon-twitter2"></i>Twitter</h5>
            <?= $form->field($campaign, 'twitterEnable')->label(false)->checkbox([], false); ?>
            <?= $form->field($campaign, 'twitterName', ['autoPlaceholder' => false])->label("<b>Twitter Username</b> (https://twitter.com/<b>XXXX</b>)"); ?>
        </div>
        <!-- YouTube -->
        <div class="social cf">
            <h5><i class="icon icon-youtube2"></i>YouTube</h5>
            <?= $form->field($campaign, 'youtubeEnable')->label(false)->checkbox([], false); ?>
            <?= $form->field($campaign, 'youtubeUrl', ['autoPlaceholder' => false])->label("<b>YouTube Channel Name</b> (https://www.youtube.com/user/<b>XXXX</b>)"); ?>
        </div>
        <!-- Facebook -->
        <div class="social last cf">
            <h5><i class="icon icon-facebook"></i>Facebook</h5>
            <?= $form->field($campaign, 'facebookEnable')->label(false)->checkbox([], false); ?>
            <?= $form->field($campaign, 'facebookUrl', ['autoPlaceholder' => false])->label("<b>Facebook Username</b> (https://www.facebook.com/<b>XXXX</b>)"); ?>
        </div>
    </div>
</div>