<?
$title = $type ? $type : 'Choose a Theme';
/*that file is loaded into*/
?>
<div class="module">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h5 class="module-title">Themes</h5>
    <div class="modal-body">
        <? if (sizeof($themes) > 0): ?>
            <? foreach ($themes as $theme): ?>
            <div class='select-theme-container' data-name='<?= $theme->name ?>' data-id='<?= $theme->id ?>'>
                <a href="javascript:void(0)" onclick="selectTheme(this)">
                    <span><?= $theme->name ?></span>
                </a>
            </div>
            <? endforeach; ?>
        <? else: ?>
            <div class="noThemes">
                No themes are currently available for this layout type!
            </div>
        <? endif ?>
    </div>
</div>