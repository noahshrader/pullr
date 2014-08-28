<?php

namespace frontend\models\streamboard;

use common\models\Donation;
use yii\base\Model;
use common\models\User;
use common\models\Campaign;
use common\components\Application;

class Streamboard extends Model {
    /**
     * @param $selectedCampaigns Campaign[]
     * @return array
     */
    public static function getStats($selectedCampaigns){
        $stats = [];
        $stats = [
            'total_amountRaised' => 0,
            'total_goalAmount' => 0,
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
            $stats['total_amountRaised']+=$campaign->amountRaised;
            $stats['total_goalAmount']+=$campaign->goalAmount;
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

    /**
     * @return Campaign[]
     * @description return selected campaigns for current user
     */
    public static function getSelectedCampaigns(){
        $user = Application::getCurrentUser();
        /*we select only user campaigns, without parent campaigns */
        return Campaign::find()->from(Campaign::tableName().' campaign')->where(['campaign.userId' => $user->id, 'status' => Campaign::STATUS_ACTIVE])
            ->joinWith(['streamboard' => function($q) use ($user){
                   $q->from(StreamboardCampaign::tableName().' streamboard')->where(['streamboard.userId' => $user->id]);
                }])
            ->andWhere('streamboard.selected = true')->orderBy('campaign.id DESC')->all();
    }

}
