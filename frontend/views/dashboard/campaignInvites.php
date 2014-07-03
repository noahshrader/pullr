<div>
    <div>
        <h2>Campaign Invites</h2>
    </div>
    <br />
    <div class="row" style="font-weight: bold">
        <div class="col-lg-4">Campaign Name</div>
        <div class="col-lg-4">Campaign Owner Name</div>
        <div class="col-lg-4">Actions</div>
    </div>
<? foreach ($campaignInvites as $invite): ?>
<div class="row">
    <div class="col-lg-4">
        <?= $invite->campaign->name ?>
    </div>
    <div class="col-lg-4">
        <?= $invite->campaign->user->name ?>
    </div>
    <div class="col-lg-4">
        <a href="app/dashboard/inviteapprove?id=<?= $invite->id ?>" class="btn btn-success">Approve</a>
        <a href="app/dashboard/invitedelete?id=<?= $invite->id ?> " class="btn btn-danger">Delete</a>
    </div>
</div>
<? endforeach; ?>

</div>