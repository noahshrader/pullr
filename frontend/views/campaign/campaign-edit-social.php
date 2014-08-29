<div id="collapseFour" class="<?= ($campaign->twitterEnable? 'twitterEnable ': '').($campaign->youtubeEnable? 'youtubeEnable ': '').($campaign->facebookEnable? 'facebookEnable ': '') ?> socialenable">
	<!-- Twitter -->
	<div class="form-group">
    	<?= $form->field($campaign, 'twitterEnable')->label('<i class="icon-twitter"></i>Enable Twitter Link?')->checkbox([], false); ?>
    	<?= $form->field($campaign, 'twitterName', ['autoPlaceholder' => false])->label("Twitter Username"); ?>
    </div>
    <!-- YouTube -->
    <div class="form-group">
    	<?= $form->field($campaign, 'youtubeEnable')->label('<i class="icon-youtube"></i>Enable Youtube?')->checkbox([], false); ?>
    	<?= $form->field($campaign, 'youtubeUrl', ['autoPlaceholder' => false])->label("YouTube Channel URL"); ?>
    </div>
    <!-- Facebook -->
    <div class="form-group">
    	<?= $form->field($campaign, 'facebookEnable')->label('<i class="icon-facebook"></i>Enable Facebook?')->checkbox([], false); ?>
    	<?= $form->field($campaign, 'facebookUrl', ['autoPlaceholder' => false])->label("Facebook Profile"); ?>
    </div>
</div>