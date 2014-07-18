<?
$title = $type ? $type : 'Choose a theme';
/*that file is loaded into*/
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h5 class="modal-title" id="myModalLabel"><?= $title ?></h5>
</div>
<div class="modal-body">

    <? if (sizeof($themes) > 0): ?>
        <div class="row">
        <? foreach ($themes as $theme): ?>
            <div class='col-xs-3 text-center select-theme-container' data-name='<?= $theme->name ?>'
                 data-id='<?= $theme->id ?>'>
                <div class='select-theme text-center'>
                    <a href='javascript:void(0)' onclick="selectTheme(this)">Select Theme</a>
                </div>
                <div><?= $theme->name ?></div>
            </div>
        <? endforeach; ?>
        </div>
    <? else: ?>
        <div class="noThemes">
            No themes are available for this type of layout - <b><?= $type ?></b>.
        </div>
    <? endif ?>
</div>
    