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
        <div class='select-theme-container' data-name='<?= $theme->name ?>'>
            <a href="javascript:void(0)" onclick="selectTheme(this)">
                <h5><?= $theme->description ?></h5>
            </a>
        </div>
        <? endforeach; ?>
    <? else: ?>
        <div class="noThemes">
            No themes are currently available for this layout type!
        </div>
    <? endif ?>
</div>
    