<div id="collapseFour" class="<?= ($campaign->twitterEnable? 'twitterEnable ': '').($campaign->youtubeEnable? 'youtubeEnable ': '').($campaign->facebookEnable? 'facebookEnable ': '') ?>">
	<!-- Twitter -->
	<div class="form-group">
    	<?= $form->field($campaign, 'twitterEnable')->label('Enable Twitter Link?')->checkbox([], false); ?>
    	<?= $form->field($campaign, 'twitterName', ['autoPlaceholder' => false])->label("Twitter Username"); ?>
    </div>
    <!-- YouTube -->
    <div class="form-group">
    	<?= $form->field($campaign, 'youtubeEnable')->label('Enable Youtube?')->checkbox([], false); ?>
    	<?= $form->field($campaign, 'youtubeUrl', ['autoPlaceholder' => false])->label("YouTube Channel URL"); ?>
    </div>
    <!-- Facebook -->
    <div class="form-group">
    	<?= $form->field($campaign, 'facebookEnable')->label('Enable Facebook?')->checkbox([], false); ?>
    	<?= $form->field($campaign, 'facebookUrl', ['autoPlaceholder' => false])->label("Facebook Profile"); ?>
    </div>
</div>