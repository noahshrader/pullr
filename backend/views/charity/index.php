<?php
use yii\helpers\Html; 
use common\assets\DataTableAsset;
use common\models\Charity;
use yii\widgets\ActiveForm;

DataTableAsset::register($this);
$this->registerJSFile('@web/js/charity/index.js', DataTableAsset::className());

$this->title = 'Charities';
?>
<h1><?= Html::encode($this->title) ?></h1>
<a href="charity/add" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Add new</a>
<br />
<br />
<?php $form = ActiveForm::begin(); ?>
<label>
    Status:
    <select class="form-control" name='status' onchange="$(this).parents('form').submit()">
        <?php foreach (Charity::$STATUSES as $_status): ?>
            <option <?php if (isset($status) && $status == $_status) echo 'selected' ?>><?= $_status ?></option>
        <?php endforeach; ?>
    </select>
</label>
<table id="charities-management-table"  class="table table-striped table-bordered table-hover dataTable">
    <thead>
        <tr>
            <th>
                (Charity Logo)
            </th>
            <th>
                Charity Organization
            </th>
            <th>
                Charity Type
            </th>
            <th>
                Paypal Address
            </th>
            <th>
                Contact
            </th>
            <th>
                Contact Email
            </th>
            <th>
                Date Added
            </th>
            <th>
                Status
            </th>
        </tr>
    </thead>
    <tbody>
        <? foreach ($charities as $charity): ?>
            <tr data-id='<?= $charity->id?>'>
                <td class="text-center"><a href="charity/edit?id=<?= $charity->id ?>"><img title="<?= $charity->name ?>" class="microPhoto" src="<?= $charity->smallPhoto ?>"></a></td>
                <td><a href="charity/edit?id=<?= $charity->id ?>"><?= $charity->name ?></a></td>
                <td><?= $charity->type?> </td>
                <td><?= $charity->paypal?> </td>
                <td><?= $charity->contact?> </td>
                <td><?= $charity->contactEmail?> </td>
                <td><?= $charity->date ?></td>
                <? switch ($charity->status){
                        case (Charity::STATUS_ACTIVE):
                            $class = 'success';
                            break;
                        case (Charity::STATUS_PENDING):
                            $class = 'warning';
                            break;
                        case (Charity::STATUS_DELETED):
                            $class = 'danger';
                            break;
                        default:
                            break;
                   } 
                ?>
                <td><span class="label label-<?= $class ?>"><?= $charity->status ?></span></td>
            </tr>
        <? endforeach; ?>
    </tbody>
</table>

<?php ActiveForm::end(); ?>