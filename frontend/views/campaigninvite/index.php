<div>
    <div>
        <h1>Campaign Invites</h1>
    </div>
    <br />
    <div class="row" style="font-weight: bold">
        <div class="col-lg-4">Campaign Name</div>
        <div class="col-lg-4">Campaign Owner Name</div>
        <div class="col-lg-4">Actions</div>
    </div>
<? foreach ($invites as $invite): ?>
<div class="row">
    <div class="col-lg-4">
        <?= $invite->campaign->name ?>
    </div>
    <div class="col-lg-4">
        <?= $invite->user->name ?>
    </div>
    <div class="col-lg-4">
        <a href="app/campaigninvite/approve?id=<?= $invite->id ?>" class="btn btn-success">Approve</a>
        <a href="app/campaigninvite/delete?id=<?= $invite->id ?> " class="btn btn-danger">Delete</a>
    </div>
</div>
<? endforeach; ?>

</div>