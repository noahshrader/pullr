<?php
namespace common\components\message;
use common\components\Application;
use common\models\Donation;
use common\models\Campaign;
use common\models\twitch\TwitchFollow;
use common\models\twitch\TwitchSubscription;
class ActivityMessage
{
    const TEMPLATE_NEW_CAMPAIGN = 'A new campaign, %s was created';
    const TEMPLATE_DONATION_RECEIVED = '%s donated $%s to %s!';
    const TEMPLATE_CAMPAIGN_ENDED = 'The campaign %s has ended with a total of $%s';
    const TEMPLATE_GOAL_REACHED = 'Congratulations! You reached your goal of $%s for %s!';
    const TEMPLATE_NEW_TWITCH_FOLLOWER = '%s just followed your channel %s!';
    const TEMPLATE_NEW_TWITCH_SUBSCRIBER = '%s just subscribed to your channel %s!';

    public static function  messageNewCampaign(Campaign $campaign){
        return sprintf(self::TEMPLATE_NEW_CAMPAIGN, $campaign->name);
    }

    public static function  messageDonationReceived(Donation $donation){
        return sprintf(self::TEMPLATE_DONATION_RECEIVED, $donation->getName(false), number_format($donation->amount), $donation->campaign->name);
    }

    public static function  messageCampaignEnded(Campaign $campaign){
        return sprintf(self::TEMPLATE_CAMPAIGN_ENDED, $campaign->name, number_format($campaign->amountRaised));
    }

    public static function  messageGoalReached(Campaign $campaign){
        return sprintf(self::TEMPLATE_CAMPAIGN_ENDED, number_format($campaign->amountRaised), $campaign->name);
    }

    public static function messageNewTwitchFollower($displayName){
        $user = Application::getCurrentUser();
        return sprintf(self::TEMPLATE_NEW_TWITCH_FOLLOWER, $displayName,$user->userFields->twitchChannel);
    }

    public static function messageNewTwitchSubscriber($displayName){        
        $user = Application::getCurrentUser();
        return sprintf(self::TEMPLATE_NEW_TWITCH_SUBSCRIBER, $displayName,$user->userFields->twitchChannel);
    }
}
