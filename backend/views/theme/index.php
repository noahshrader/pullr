<?php
use yii\helpers\Html; 
use yii\widgets\ActiveForm;
use common\models\Theme;

$this->registerJSFile('@web/js/event/index.js',  \common\assets\CommonAsset::className());

$this->title = 'Themes';
?>

<div class="admin-content-wrap">

    <h1><?= Html::encode($this->title) ?></h1>
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

    <table id="themes-management-table"  class="table table-striped table-bordered table-hover dataTable">
        <thead>
            <tr>
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
            </tr>
        </thead>
        <tbody>
            <? foreach ($themes as $theme): ?>
                <tr>
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
                </tr>
            <? endforeach; ?>
        </tbody>
    </table>
    <? ActiveForm::end() ?>

</div>