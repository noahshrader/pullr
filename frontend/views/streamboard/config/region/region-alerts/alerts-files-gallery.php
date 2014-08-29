<?
use yii\helpers\Url;
use frontend\models\streamboard\WidgetAlertsPreference;
/**@var $fileType 'sound'|'image'*/
?>
<div child-scope ng-init="fileType = '<?= $fileType ?>';uploadError = null;">
    <!--main variable - preference -->
    <ul class="nav nav-tabs custrom-or-library-tabs">
        <li><a href="<?= Url::to() ?>#{{baseLink}}-{{fileType}}s-custom" data-toggle="tab">Yours</a></li>
        <li class="active"><a href="<?= Url::to() ?>#{{baseLink}}-{{fileType}}s-library" data-toggle="tab">Library</a>
        </li>
    </ul>
    <div class="tab-content sounds-graphics-content">
        <div id="{{baseLink}}-{{fileType}}s-custom" class="tab-pane">
            <div class="error">
                {{error}}
            </div>
            <input type="file" ng-file-select="onFileUpload($files, fileType, this)">

            <div class="uploader" ng-file-drop="onFileUpload($files, fileType, this)" ng-file-drag-over-class="uploader-drag-over">
                Drops Files Here
            </div>
            <div class="files-container">
                <div ng-repeat="file in (fileType=='sound' ? alertMediaManagerService.customSounds : alertMediaManagerService.customImages)"
                     ng-init='libraryType=<?= json_encode(WidgetAlertsPreference::FILE_TYPE_CUSTOM) ?>'>
                    <?= $this->render('alerts-'.$fileType) ?>
                </div>
            </div>
        </div>
        <div>
            <i class="glyphicon glyphicon-volume-up"></i>
            <slider ng-model="preference.volume" floor="0" ceiling="100" step="1"
                    ng-change="regionChanged(region)"></slider>
        </div>
        <div id="{{baseLink}}-{{fileType}}s-library" class="tab-pane active">
            <div class="files-container">
                <div ng-repeat="file in (fileType=='sound' ? alertMediaManagerService.librarySounds : alertMediaManagerService.libraryImages)"
                     ng-init='libraryType=<?= json_encode(WidgetAlertsPreference::FILE_TYPE_LIBRARY) ?>'>
                    <?= $this->render('alerts-'.$fileType) ?>
                </div>
            </div>
        </div>
    </div>
</div>
