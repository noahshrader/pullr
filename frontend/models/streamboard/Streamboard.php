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
     */
    public static function getStats($selectedCampaigns){
        $stats = [];
        $stats = [
            'number_of_donations' => 0,
            'total_amount' => 0,
            'number_of_donors' => 0,
            'top_donation_amount' => 0,
            'top_donation_name' => 0,
        ];

        $ids = [];
        foreach ($selectedCampaigns as $campaign){
            $ids[] = $campaign->id;
        }
        $stats['top_donors'] = Donation::getTopDonorsForCampaigns($selectedCampaigns, 3, true);

        return $stats;
    }

}
