
<div>
    <h2>[ID = <?= $user->id ?>] <strong><?= $user->name ?></strong> deactivated account.</h2>
    <br>
    <div><h4>Reason:</h4></div>
    <? if ($reason): ?>
        <div><i>
                <?= $reason ?>
            </i>
        </div>
    <? else: ?>
    <div>
        He didn't set reason for deactivation.
    </div>
    <? endif; ?>
</div>