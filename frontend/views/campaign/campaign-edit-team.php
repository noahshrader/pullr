<div id="campaign-edit-team">
    <h2>Expand your fundraiser with teams.</h2>
    <div class="module">

        <h5><i class="icon-adduser"></i>Team Invites</h5>
        <? if ($campaign->isNewRecord): ?>
            <div class="label label-danger">Save campaign before adding emails</div>
        <? else: ?>
        <div id="campaign-invites">
            <div id="addCampaingInviteInfo" class="label label-danger"></div>
            <label>Invite Pullr users to your team <i class="icon icon-help" data-toggle="tooltip" data-placement="top" title="Type in your Pullr invitee's email address they used to connect with Twitch. Support for channel names coming soon."></i></label>
            <div class="combined-form-wrap">
                <input type="text" id="addCampaignInvite" placeholder="Add Twitch Email Address" class="form-control">
                <a onclick="addNewCampaignInvite()" class="icon icon-plus"></a>
            </div>
            <div id="campaignInvitesUsers" class="team-list"></div>
        </div>
        <? endif ?>
    </div>
</div>