<?
use common\models\Campaign;
use yii\helpers\Html;
?>

<div class="panel panel-default">
        <div class="panel-heading"><div class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                Social Integration
            </a>
        </div></div>
        <div id="collapseFour" class="panel-collapse collapse <?= ($campaign->twitterEnable? 'twitterEnable ': '').($campaign->youtubeEnable? 'youtubeEnable ': '').($campaign->facebookEnable? 'facebookEnable ': '') ?>">

            <?= $form->field($campaign, 'twitterEnable')->label('Enable Twitter Link?')->checkbox([], false); ?>
            <?= $form->field($campaign, 'twitterName', ['autoPlaceholder' => true]); ?>
            <?= $form->field($campaign, 'youtubeEnable')->label('Enable Youtube?')->checkbox([], false); ?>
            <?= $form->field($campaign, 'youtubeUrl', ['autoPlaceholder' => true]); ?>
            <?= $form->field($campaign, 'includeYoutubeFeed')->label('Include Youtube Feed?')->checkbox([], false); ?>
            <?= $form->field($campaign, 'facebookEnable')->label('Enable Facebook?')->checkbox([], false); ?>
            <?= $form->field($campaign, 'facebookUrl', ['autoPlaceholder' => true]); ?>
        </div>
</div>