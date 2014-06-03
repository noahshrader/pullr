<?
    $title = $type ? $type : 'Choose a theme';
?>

    <h5 class="modal-title" id="myModalLabel"><?= $title ?></h5>

    <div class="row">
        <? foreach ($themes as $theme):?>
        <div class='col-xs-3 text-center select-theme-container' data-name='<?= $theme->name ?>' data-id='<?= $theme->id ?>'>
            <div class='select-theme text-center' >
                <a href='javascript:void(0)' onclick="selectTheme(this)">Select Theme</a>
            </div>
            <div ><?= $theme->name ?></div>
        </div>
        <? endforeach; ?>
    </div>

    