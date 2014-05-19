<div id="campaign-edit-team">
    <h4>Invite members</h4>
     <? if ($campaign->isNewRecord): ?>
            <div class="label label-danger">Save campaign before adding emails</div>
            <br>
    <? else: ?>
    <div id="campaign-invites">
        <div id="addCampaingInviteInfo" class="label label-danger"></div>
        <input type="text" id="addCampaignInvite" placeholder="Add Email Address"> <a class="btn btn-success btn-xs" onclick="addNewCampaignInvite()"> <i class="glyphicon glyphicon-plus"></i></a>
        <div id="campaignInvitesUsers">
            
        </div>
    </div>
    <? endif ?>
</div>