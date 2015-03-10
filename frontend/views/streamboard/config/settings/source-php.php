<?
use yii\helpers\Url;
use yii\web\View;
use common\components\PullrUtils;

/**@var $this View */
?>
<div class="source-wrap" id="source-wrap-php">
    <? if ($twitchUser != null): ?>
    <div class="twitchStats module">
        <div class="form-group">
            <h5>Twitch</h5>
            <div>Followers: <span id="followers_number" class="amount accent"><?= $followersNumber;?></span>
            </div>
            <? if ( ! empty($twitchPartner)): ?>
                <div>Subscribers:
                    <span id="subscriber_number" class="amount accent"><?= $subscribersNumber; ?></span>
                </div>
            <? endif; ?>
        </div>
    </div>
    <? endif; ?>
    <div class="activityFeed module">
        <div class="form-group">
            <h5>Activity Feed</h5>
            <div class="sb-activity-feed"><?
                $activityFeed = $this->render('source-activity-feed', [
                    'donors' => $donors,
                    'subscribers' => $subscribers,
                    'followers' => $followers,
                    'showSubscriber' => $showSubscriber,
                    'showFollower' => $showFollower,
                    'emptyActivityMessage' => $emptyActivityMessage,
                    'groupUser' => $groupUser,
                    'groupDonors' => $groupDonors,
                ]);
                echo preg_replace("/\s+/", " ", trim($activityFeed));
            ?></div>
        </div>
    </div>

    <? if (count($campaigns) > 0): ?>
    <div class="tags module">
        <div class="form-group">
            <h5>Tags</h5>
            <div>Last Follower: <span id='last_follower'><?= (!empty($followers)) ? $followers[0]['display_name'] : '' ?></span></div>
            <div>Last Subscriber: <span id='last_subscriber'><?= (!empty($subscribers)) ? $subscribers[0]['display_name'] : '' ?></span></div>

            <div>Last Donor: <span id='last_donor'><?= isset($stats['last_donor']) ? $stats['last_donor']['name']:'' ?></span></div>
            <div>Last Donor/Amount: <span id='last_donor_donation'><?= isset($stats['last_donor']) ? $stats['last_donor']['name']. ' ($'. $stats['last_donor']['amount'].')': '' ?></span></div>

            <div>Largest Donation: <span id='largest_donation'><?= (!empty($stats['top_donation'])) ? '$'. $stats['top_donation']['amount'] .' ('.  $stats['top_donation']['displayName'] .')' : ''  ?></span></div>

            <div>Top Donor: <span><?= isset($stats['top_donors'][0]) ? $stats['top_donors'][0]['name'] : '' ?></span></div>
            <div>Top Donor/Amount: <span id='top_donor'><?= isset($stats['top_donors'][0]) ? $stats['top_donors'][0]['name'] .' ($'. $stats['top_donors'][0]['amount']  .')' : '' ?></span></div>
            <div>
                Top 3 Donors:<br>
                <?php foreach($stats['top_donors'] as $donor):?>
                <span><?= $donor['name'] .' ($'. PullrUtils::formatNumber($donor['amount'], 2)  .')' ?></span><br>
                <?php endforeach;?>
            </div>

        </div>
    </div>
    <?php endif; ?>

    <? if (count($campaigns) > 0): ?>
    <div class="overall module">
        <div class="form-group">
            <h5>Overall</h5>
            <div>Total Amount Raised: <span id="total_amount_raised" class="amount accent">$<?= $stats['total_amountRaised']; ?></span>
            </div>
            <div>Total Goal Amount: <span id="total_goal_amount" class="amount accent">$<?= $stats['total_goalAmount']; ?></span>
            </div>
            <div>Total Donations: <span id="total_donations" class="amount accent"><?= $stats['number_of_donations']; ?></span>
            </div>
            <div>Total Donors: <span id="total_donors" class="amount accent"><?= $stats['number_of_donors']; ?></span></div>
        </div>
    </div>
    <? endif; ?>

    <? foreach ($campaigns as $campaign): ?>
    <div id="campaign_<?= $campaign['id'];?>" class="source-row module campaign">
        <div class="form-group">
            <h5 id="campaignName_<?= $campaign['id'];?>"><?= $campaign['name']; ?></h5>
            <div>Amount Raised: <span class="amount accent" id="amountRaised_<?= $campaign['id'];?>">$<?= $campaign['amountRaised']; ?></span>
            </div>
            <div>Goal Amount: <span class="amount accent" id="goalAmount_<?= $campaign['id'];?>">$<?= $campaign['goalAmount']; ?></span>
            </div>
            <div>Donations: <span class="amount accent" id="donations_<?= $campaign['id'];?>"><?= $campaign['numberOfDonations']; ?></span>
            </div>
            <div>Donors: <span class="amount accent" id="donors_<?= $campaign['id'];?>"><?= $campaign['numberOfUniqueDonors']; ?></span>
            </div>
        </div>
    </div>
    <? endforeach; ?>

</div>
