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
        <div class='select-theme-container' data-name='<?= $theme->name ?>'
             data-id='<?= $theme->id ?>'>
            <a href='javascript:void(0)' onclick="selectTheme(this)">Select Theme</a>
            <h4><?= $theme->name ?></h4>
        </div>
        <? endforeach; ?>
    <? else: ?>
        <div class="noThemes">
            No themes are currently available for this layout type.
        </div>
    <? endif ?>
</div>
    