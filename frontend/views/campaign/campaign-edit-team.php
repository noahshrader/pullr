<div id="campaign-edit-team">
    <h4>Invite members</h4>
     <? if ($campaign->isNewRecord): ?>
            <div class="label label-danger">Save campaign before adding emails</div>
            <br>
    <? else: ?>
    <div id="campaign-invites">
        <div id="addCampaingInviteInfo" class="label label-danger"></div>
        <div class="form-horizontal">
            <input type="text" id="addCampaignInvite" placeholder="Add Email Address" class="form-control"> <a class="btn btn-success btn-xs" onclick="addNewCampaignInvite()"> <i class="icon icon-add2"></i></a>
        </div>
        <div id="campaignInvitesUsers">
            
        </div>
    </div>
    <? endif ?>
</div>