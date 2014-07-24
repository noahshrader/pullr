<div class="campaign-invites"> <!-- BEGIN campaign invites -->
    <h5>Campaign Invites</h5>
    <ul>
        <? foreach ($campaignInvites as $invite): ?>
        <li>
            <h4><?= $invite->campaign->name ?></h4>
            <span><?= $invite->campaign->user->name ?></span>
            <div class="invite-actions">
                <a href="app/dashboard/inviteapprove?id=<?= $invite->id ?>" class="icon-check2">Accept</a>
                <a href="app/dashboard/invitedelete?id=<?= $invite->id ?> " class="icon-remove2">Decline</a>
            </div>
        </li>
        <? endforeach; ?>
    </ul>
</div> <!-- END campaign invites -->