<?php
namespace common\components\message;

use common\components\Application;
use common\models\Donation;
use common\models\Campaign;
use common\components\PullrUtils;
use common\models\twitch\TwitchFollow;
use common\models\twitch\TwitchSubscription;
use frontend\models\streamboard\WidgetAlertsPreference;
use frontend\models\streamboard\WidgetCampaignBarAlerts;

class ActivityMessage
{
    const TEMPLATE_NEW_CAMPAIGN = 'A new campaign, %s was created';
    const TEMPLATE_DONATION_RECEIVED = '[[TwitchUser]] donated $[[Amount]] to [[CampaignName]]!';
    const TEMPLATE_CAMPAIGN_ENDED = 'The campaign %s has ended with a total of $%s';
    const TEMPLATE_GOAL_REACHED = 'Congratulations! You reached your goal of $%s for %s!';
    const TEMPLATE_NEW_TWITCH_FOLLOWER = '%s just followed your channel %s!';
    const TEMPLATE_NEW_TWITCH_SUBSCRIBER = '%s just subscribed to your channel %s!';

    public static function  messageNewCampaign(Campaign $campaign)
    {
        return sprintf(self::$TEMPLATE_NEW_CAMPAIGN, $campaign->name);
    }

    public static function  messageDonationReceived(Donation $donation)
    {
        $userId = $donation->campaign->userId;
        $row = WidgetAlertsPreference::find()->where(['userId' => $userId, 'preferenceType' => 'donations'])->one();
        if($row && $row->alertText){
            $message = $row->alertText;
        }else{
            $message = self::TEMPLATE_DONATION_RECEIVED;
        }

        $patternArray = array(
                                '[[TwitchUser]]' => $donation->getName(false),
                                '[[Donor]]' => '',
                                '[[Amount]]' => PullrUtils::formatNumber($donation->amount, 2),
                                '[[CampaignName]]' => $donation->campaign->name,
                                "\n" => ''
                            );
        return str_replace(array_keys($patternArray), array_values($patternArray), $message);
    }

    public static function  messageCampaignEnded(Campaign $campaign)
    {
        return sprintf(self::TEMPLATE_CAMPAIGN_ENDED, $campaign->name, PullrUtils::formatNumber($campaign->amountRaised, 2));
    }

    public static function  messageGoalReached(Campaign $campaign)
    {
        return sprintf(self::TEMPLATE_CAMPAIGN_ENDED, PullrUtils::formatNumber($campaign->amountRaised, 2), $campaign->name);
    }

    public static function messageNewTwitchFollower($displayName)
    {
        $user = Application::getCurrentUser();
        return sprintf(self::TEMPLATE_NEW_TWITCH_FOLLOWER, $displayName, $user->userFields->twitchChannel);
    }

    public static function messageNewTwitchSubscriber($displayName)
    {
        $user = Application::getCurrentUser();
        return sprintf(self::TEMPLATE_NEW_TWITCH_SUBSCRIBER, $displayName, $user->userFields->twitchChannel);
    }
}
