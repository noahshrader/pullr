<?
use yii\helpers\Url;
use yii\web\View;

/**@var $this View */
?>
<div class="source-wrap" id="source-wrap-php">    
    <? if ($twitchUser != null): ?>
    <div class="twitchStats module">
        <div class="form-group">
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
                        'emptyActivityMessage' => $emptyActivityMessage
                    ]);
                    echo preg_replace("/\s+/", " ", trim($activityFeed));
                ?></div>
        </div>
    </div>
    <? if (count($campaigns) > 0): ?>
    <div class="overall module">
        <div class="form-group">
            <h5>Overall</h5>
            <div>Total Amount Raised: <span id="total_amount_raised" class="amount accent">$<?= number_format($stats['total_amountRaised']); ?></span>
            </div>
            <div>Total Goal Amount: <span id="total_goal_amount" class="amount accent">$<?= number_format($stats['total_goalAmount']); ?></span>
            </div>
            <div>Total Donations: <span id="total_donations" class="amount accent"><?= number_format($stats['number_of_donations']); ?></span>
            </div>
            <div>Total Donors: <span id="total_donors" class="amount accent"><?= number_format($stats['number_of_donors']); ?></span></div>
        </div>
    </div>
    <? endif; ?>

    <? foreach ($campaigns as $campaign): ?>
    <div id="campaign_<?= $campaign['id'];?>" class="source-row module campaign">
        <div class="form-group">
            <h5 id="campaignName_<?= $campaign['id'];?>"><?= $campaign['name']; ?></h5>
            <div>Amount Raised: <span class="amount accent" id="amountRaised_<?= $campaign['id'];?>">$<?= number_format($campaign['amountRaised']); ?></span>
            </div>
            <div>Goal Amount: <span class="amount accent" id="goalAmount_<?= $campaign['id'];?>">$<?= number_format($campaign['goalAmount']); ?></span>
            </div>
            <div>Donations: <span class="amount accent" id="donations_<?= $campaign['id'];?>"><?= number_format($campaign['numberOfDonations']); ?></span>
            </div>
            <div>Donors: <span class="amount accent" id="donors_<?= $campaign['id'];?>"><?= number_format($campaign['numberOfUniqueDonors']); ?></span>
            </div>
        </div>
    </div>
    <? endforeach; ?>
    
</div>