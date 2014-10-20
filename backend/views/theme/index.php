<?php
use yii\helpers\Html; 
use yii\widgets\ActiveForm;
use common\models\Theme;

//$this->registerJSFile('@web/js/event/index.js',  \common\assets\CommonAsset::className());

$this->title = 'Themes';
?>

<div class="admin-content-wrap">

    <?php $form = ActiveForm::begin(); ?>
    <label>
        Status:
        <select class="form-control" name='status' onchange="$(this).parents('form').submit()">
            <?php foreach (Theme::$STATUSES as $_status): ?>
                <option <?php if (isset($status) && $status == $_status) echo 'selected' ?>><?= $_status ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <a href="theme/rescan" class="btn btn-primary">Rescan Themes</a>

    <table id="themes-management-table"  class="table dataTable" style="width: 700px;">
        <thead>
            <tr>
                <th>
                    Date added
                </th>
                <th>
                    Filename
                </th>
                <th>
                    Name
                </th>
                <th>
                    Description
                </th>
                <th>
                    Layout Type
                </th>
                <th>
                    Plan
                </th>
                <th>
                    Status
                </th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <? foreach ($themes as $theme): ?>
                <tr>
                    <td><?= date('Y.m.d' ,$theme->addedDate) ?></td>
                    <td><?= $theme->filename ?></td>
                    <td><?= $theme->name?> </td>
                    <td><?= $theme->description ?></td>
                    <td><?= $theme->layoutType ?></td>
                    <td><?= $theme->plan ?></td>
                    <? switch ($theme->status){
                            case (Theme::STATUS_ACTIVE):
                                $class = 'success';
                                break;
                            case (Theme::STATUS_REMOVED):
                                $class = 'danger';
                                break;
                            default:
                                break;
                       } 
                    ?>
                    <td><span class="label label-<?= $class ?>"><?= $theme->status ?></span></td>
                    <td>
                            <a href="<?= \Yii::$app->urlManager->createUrl(['theme/enable', 'themeId' => $theme->id]); ?>">on</a>
                            <a href="<?= \Yii::$app->urlManager->createUrl(['theme/disable', 'themeId' => $theme->id]); ?>">off</a>
                    </td>
                </tr>
            <? endforeach; ?>
        </tbody>
    </table>
    <? ActiveForm::end() ?>

</div>