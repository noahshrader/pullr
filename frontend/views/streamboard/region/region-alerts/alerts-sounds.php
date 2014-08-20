<?
use yii\helpers\Url;
?>
<!--main variable - preference -->
<ul class="nav nav-tabs custrom-or-library-tabs">
    <li><a href="<?= Url::to() ?>#{{baseLink}}-sounds-custom" data-toggle="tab">Yours</a></li>
    <li class="active"><a href="<?= Url::to() ?>#{{baseLink}}-sounds-library" data-toggle="tab">Library</a></li>
</ul>
<div class="tab-content sounds-graphics-content">
    <div id="{{baseLink}}-sounds-custom" class="tab-pane">
        <div class="error">
           {{soundUploadError}}
        </div>
        <input type="file" ng-file-select="onFileSelect($files,soundUploadError)">
        <div class="uploader" ng-file-drop="onFileSelect($files)" ng-file-drag-over-class="uploader-drag-over">
            Drops Files Here
        </div>
        <div class="sound-container">
            <div ng-repeat="sound in $root.AlertMediaManager.customSounds" >
                <?= $this->render('alerts-sound') ?>
            </div>
        </div>
    </div>
    <div id="{{baseLink}}-sounds-library" class="tab-pane active">
        <div class="sound-container">
            <div ng-repeat="sound in $root.AlertMediaManager.librarySounds" >
                <?= $this->render('alerts-sound') ?>
            </div>
        </div>
    </div>
</div>
