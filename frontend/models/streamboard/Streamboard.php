<?php

namespace frontend\models\streamboard;

use common\models\Donation;
use yii\base\Model;
use common\models\User;
use common\models\Campaign;
/**
 * Class Streamboard is used to store user's settings for streamboard.
 * @package frontend\models\streamboard
 * @property integer $donationId
 * @property integer $userId
 * @property boolean $nameHidden
 * @property boolean $wasRead
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
            'top_donors' => []
        ];

        $ids = [];
        foreach ($selectedCampaigns as $campaign){
            $ids[] = $campaign->id;
        }
        /*angular doesn't allow duplicates, so we should use indexes*/
        $topDonors = Donation::getTopDonorsForCampaigns($selectedCampaigns, 3, true);
        $stats['top_donors'] = $topDonors;

        foreach ($selectedCampaigns as $campaign){
            $stats['total_amount']+=$campaign->amountRaised;
        }
        $topDonation = Donation::getTopDonation($selectedCampaigns);
        if ($topDonation){
            $stats['top_donation'] = $topDonation->toArray(['id', 'nameFromForm', 'amount']);
            $stats['top_donation']['displayName'] = $topDonation->displayNameForDonation();
        } else {
            $stats['top_donation'] = null;
        }
        $stats['number_of_donations'] = Donation::getDonationsForCampaigns($selectedCampaigns)->count();
        $stats['number_of_donors']  = Donation::getDonationsForCampaigns($selectedCampaigns)->count('DISTINCT email');
        return $stats;
    }

}
