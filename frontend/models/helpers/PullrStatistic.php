<?
namespace frontend\models\helpers;

use common\models\Donation;
use common\models\Campaign;
use common\models\User;
use yii\db\Query;

class PullrStatistic {

    public function getTotalDonationAmountQuery()
    {
        $query = new Query();
        $query->select('sum(d.amount)')
            ->from(Donation::tableName() . ' d')
            ->leftJoin(Campaign::tableName() . ' c', 'c.id = d.campaignId')
            ->leftJoin(User::tableName() . ' u', 'u.id = c.userId')
            ->where(['c.status' => [Campaign::STATUS_ACTIVE, Campaign::STATUS_PENDING]])
            ->andWhere(['d.isManual' => 0])
            ->andWhere(['not',['d.paymentDate' => 0]])
            ->andWhere(['not', ['u.role' => User::ROLE_ADMIN]]);
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