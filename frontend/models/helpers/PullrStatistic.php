<?
namespace frontend\models\helpers;

use common\models\Donation;
use common\models\Campaign;
use yii\db\Query;

class PullrStatistic {

    public function getTotalDonationAmountQuery()
    {
        $query = new Query();
        $query->select('sum(d.amount)')
            ->from(Donation::tableName() . ' d')
            ->leftJoin(Campaign::tableName() . ' c', 'c.id = d.campaignId')
            ->where(['c.status' => [Campaign::STATUS_ACTIVE, Campaign::STATUS_PENDING]]);
        return $query;
    }
    public function getTotalDonationFromCharityCampaign()
    {
        $query = $this->getTotalDonationAmountQuery()
            ->andWhere(['c.type' => Campaign::TYPE_CHARITY_FUNDRAISER]);
        $command = $query->createCommand();
        $amount = $command->queryScalar();
        return $amount;
    }

    public function getTotalDonationFromPersonalCampaign()
    {
        $query = $this->getTotalDonationAmountQuery()
                    ->andWhere(['c.type' => Campaign::TYPE_PERSONAL_FUNDRAISER]);
        $command = $query->createCommand();
        $amount = $command->queryScalar();
        return $amount;
    }

    public function getTotalDonation()
    {
        $query = $this->getTotalDonationAmountQuery();
        $command = $query->createCommand();
        $amount = $command->queryScalar();
        return $amount;
    }


}