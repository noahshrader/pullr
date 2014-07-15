<?php

namespace frontend\models\streamboard;

use common\models\Donation;
use yii\base\Model;
use common\models\User;
use common\models\Campaign;
/**
 * Class Streamboard is used to store user's settings for streamboard.
 * @package frontend\models\streamboard
 */
class Streamboard extends Model {
    /**
     * @param $selectedCampaigns Campaign[]
     * @return array
     */
    public static function getStats($selectedCampaigns){
        $stats = [];
        $stats = [
            'total_amount' => 0,
        ];

        $ids = [];
        foreach ($selectedCampaigns as $campaign){
            $ids[] = $campaign->id;
        }
        $stats['top_donors'] = Donation::getTopDonorsForCampaigns($selectedCampaigns, 3, true);
        foreach ($selectedCampaigns as $campaign){
            $stats['total_amount']+=$campaign->amountRaised;
        }
        $topDonation = Donation::getTopDonation($selectedCampaigns);
        $stats['top_donation'] = $topDonation ? $topDonation->toArray(['id', 'nameFromForm', 'amount']) : null;
        $stats['number_of_donations'] = Donation::getDonationsForCampaigns($selectedCampaigns)->count();
        $stats['number_of_donors']  = Donation::getDonationsForCampaigns($selectedCampaigns)->count('DISTINCT email');
        return $stats;
    }

}
