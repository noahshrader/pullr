<div id="collapseFour" class="<?= ($campaign->twitterEnable? 'twitterEnable ': '').($campaign->youtubeEnable? 'youtubeEnable ': '').($campaign->facebookEnable? 'facebookEnable ': '') ?> socialenable">
    <div class="social">
        <!-- Twitter -->
        <div class="module-inner cf">
            <h5><i class="icon mdib-twitter2"></i></h5>
            <?= $form->field($campaign, 'twitterEnable')->label(false)->checkbox([], false); ?>
            <?= $form->field($campaign, 'twitterName', ['autoPlaceholder' => false, 'template' => '<div class="highlight-wrap float">{label}{input}</div>'])->label("Twitter Username")->textInput(array('placeholder' => 'eg @pullr')); ?>
        </div>
        <!-- YouTube -->
        <div class="module-inner cf">
            <h5><i class="icon mdib-youtube2"></i></h5>
            <?= $form->field($campaign, 'youtubeEnable')->label(false)->checkbox([], false); ?>
            <?= $form->field($campaign, 'youtubeUrl', ['autoPlaceholder' => false, 'template' => '<div class="highlight-wrap float">{label}{input}</div>'])->label("YouTube Channel")->textInput(array('placeholder' => 'eg https://www.youtube.com/user/xxxx')); ?>
        </div>
        <!-- Facebook -->
        <div class="module-inner cf">
            <h5><i class="icon mdib-facebook"></i></h5>
            <?= $form->field($campaign, 'facebookEnable')->label(false)->checkbox([], false); ?>
            <?= $form->field($campaign, 'facebookUrl', ['autoPlaceholder' => false, 'template' => '<div class="highlight-wrap float">{label}{input}</div>'])->label("Facebook URL")->textInput(array('placeholder' => 'eg https://www.facebook.com/xxxx')); ?>
        </div>
    </div>
</div>