<div class="campaign-invites module"> <!-- BEGIN campaign invites -->
    <h5 class="module-title">Invites</h5>
    <ul>
        <? foreach ($campaignInvites as $invite): ?>
        <li>
            <h4><?= $invite->campaign->name ?></h4>
            <span><?= $invite->campaign->user->name ?></span>
            <div class="invite-actions cf">
                <a href="app/dashboard/inviteapprove?id=<?= $invite->id ?>"><i class="icon-check2"></i> Accept</a>
                <a href="app/dashboard/invitedelete?id=<?= $invite->id ?> "><i class="icon-remove2"></i> Decline</a>
            </div>
        </li>
        <? endforeach; ?>
    </ul>
</div> <!-- END campaign invites -->