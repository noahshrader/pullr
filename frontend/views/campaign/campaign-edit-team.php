<div id="campaign-edit-team">
    <div class="module">
        <h4>Team Fundraising</h4>

        <? if ($campaign->isNewRecord): ?>
            <div class="label label-danger">Save campaign before adding emails</div>
        <? else: ?>
        <div id="campaign-invites">
            <div id="addCampaingInviteInfo" class="label label-danger"></div>
            <label>Invite Pullr users to your team <i class="icon icon-help" data-toggle="tooltip" data-placement="top" title="Select the donation destination."></i></label>
            <div class="combined-form-wrap">
                <input type="text" id="addCampaignInvite" placeholder="Add Email Address" class="form-control">
                <a onclick="addNewCampaignInvite()" class="icon icon-plus"></a>
            </div>
            <div id="campaignInvitesUsers" class="team-list"></div>
        </div>
        <? endif ?>
    </div>
</div>