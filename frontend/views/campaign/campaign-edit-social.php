<div id="collapseFour" class="<?= ($campaign->twitterEnable? 'twitterEnable ': '').($campaign->youtubeEnable? 'youtubeEnable ': '').($campaign->facebookEnable? 'facebookEnable ': '') ?> socialenable">
    <h4>Social Settings</h4>

	<!-- Twitter -->
	<div class="form-group">
    	<?= $form->field($campaign, 'twitterEnable')->label('<i class="icon-twitter"></i>')->checkbox([], false); ?>
    	<?= $form->field($campaign, 'twitterName', ['autoPlaceholder' => false])->label("Twitter Username"); ?>
    </div>
    <!-- YouTube -->
    <div class="form-group">
    	<?= $form->field($campaign, 'youtubeEnable')->label('<i class="icon-youtube"></i>')->checkbox([], false); ?>
    	<?= $form->field($campaign, 'youtubeUrl', ['autoPlaceholder' => false])->label("YouTube Channel URL"); ?>
    </div>
    <!-- Facebook -->
    <div class="form-group">
    	<?= $form->field($campaign, 'facebookEnable')->label('<i class="icon-facebook"></i>')->checkbox([], false); ?>
    	<?= $form->field($campaign, 'facebookUrl', ['autoPlaceholder' => false])->label("Facebook Profile"); ?>
    </div>
</div>