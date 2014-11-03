<?
    $title = 'Choose a charity';
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<div class="modal-body">

    <h5 class="modal-title" id="myModalLabel"><?= $title ?></h5>
    <div class="row">
        <? foreach ($charities as $charity):?>
        <div class='col-xs-12 select-charity-container' onclick="selectCharity(this)" data-name='<?= $charity->name ?>' data-id='<?= $charity->id ?>'>
            <div class="col-xs-4">
                <img title="<?= $charity->name ?>" class="microPhoto" src="<?= $charity->smallPhoto ?>">
            </div>
            <div class="col-xs-8">
                <div><strong><?= $charity->name ?></strong></div>
                <div><?= $charity->description ?></div>
            </div>
        </div>
        <? endforeach; ?>
    </div>

</div>
    