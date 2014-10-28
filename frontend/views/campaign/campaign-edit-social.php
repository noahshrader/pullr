<div id="collapseFour" class="<?= ($campaign->twitterEnable? 'twitterEnable ': '').($campaign->youtubeEnable? 'youtubeEnable ': '').($campaign->facebookEnable? 'facebookEnable ': '') ?> socialenable">
    
    <h3>Connect</h3>
	<!-- Twitter -->
	<div class="module cf">
        <h5><i class="icon-twitter2"></i>Twitter</h5>
    	<?= $form->field($campaign, 'twitterEnable')->label(false)->checkbox([], false); ?>
    	<?= $form->field($campaign, 'twitterName', ['autoPlaceholder' => false])->label("Twitter Username"); ?>
    </div>
    <!-- YouTube -->
    <div class="module cf">
        <h5><i class="icon-youtube2"></i>YouTube</h5>
    	<?= $form->field($campaign, 'youtubeEnable')->label(false)->checkbox([], false); ?>
    	<?= $form->field($campaign, 'youtubeUrl', ['autoPlaceholder' => false])->label("YouTube Channel URL"); ?>
    </div>
    <!-- Facebook -->
    <div class="module cf">
        <h5><i class="icon-facebook"></i>Facebook</h5>
    	<?= $form->field($campaign, 'facebookEnable')->label(false)->checkbox([], false); ?>
    	<?= $form->field($campaign, 'facebookUrl', ['autoPlaceholder' => false])->label("Facebook Profile"); ?>
    </div>
</div>