<div class="campaign-invites"> <!-- BEGIN campaign invites -->
    <ul>
        <? foreach ($campaignInvites as $invite): ?>
        <li>
            <p><?= $invite->campaign->name ?></p>
            <span><?= $invite->campaign->user->name ?></span>
            <div class="invite-actions cf">
                <a href="app/dashboard/inviteapprove?id=<?= $invite->id ?>" class="accept">Accept</a>
                <a href="app/dashboard/invitedelete?id=<?= $invite->id ?> " class="decline">Decline</a>
            </div>
        </li>
        <? endforeach; ?>
    </ul>
</div> <!-- END campaign invites -->