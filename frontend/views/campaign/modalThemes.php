<?
$title = $type ? $type : 'Choose a Theme';
/*that file is loaded into*/
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<div class="modal-body">

    <? if (sizeof($themes) > 0): ?>
        <? foreach ($themes as $theme): ?>
        <a class='select-theme-container' data-name='<?= $theme->name ?>'
             data-id='<?= $theme->id ?>'>
            <h5><?= $theme->name ?></h5>
        </a>
        <? endforeach; ?>
    <? else: ?>
        <div class="noThemes">
            No themes are currently available for this layout type!
        </div>
    <? endif ?>
</div>
    