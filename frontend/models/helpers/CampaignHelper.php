<?
namespace frontend\models\helpers;

use common\models\Donation;
use common\models\Campaign;
use common\models\User;
use common\models\FeaturedCampaign;
use yii\db\Query;

class CampaignHelper {
    public static function getCharityCampaigns()
    {
        return Campaign::find()->where(['type' => Campaign::TYPE_CHARITY_FUNDRAISER])
                                ->andWhere(['status' => Campaign::STATUS_ACTIVE])
                                ->all();
    }

    public static function getFeaturedCampaignByUser($user)
    {
        return Campaign::find()
            ->from(Campaign::tableName() . ' c')
            ->rightJoin(FeaturedCampaign::tableName() . ' fc', 'fc.campaignId = c.id')
            ->where(['fc.userId' => $user->id])
            ->all();
    }

    public static function getFeaturedCampaigns()
    {
        return Campaign::find()
                        ->from(Campaign::tableName() . ' c')
                        ->rightJoin(FeaturedCampaign::tableName() . ' fc', 'fc.campaignId = c.id')
                        ->leftJoin(User::tableName() . ' u', 'u.id = fc.userId')
                        ->where(['c.status' => Campaign::STATUS_ACTIVE])
                        ->andWhere([
                            'u.status' => User::STATUS_ACTIVE,
                            'u.enableFeaturedCampaign' => 1
                        ])
                        ->andWhere('(c.layoutType = :singleStreamLayout and c.channelName != "") or (c.layoutType = :teamLayout and c.channelTeam !="") or (c.layoutType = :multiStreamLayout)')
                        ->addParams([
                            'singleStreamLayout' => Campaign::LAYOUT_TYPE_SINGLE,
                            'teamLayout' => Campaign::LAYOUT_TYPE_TEAM,
                            'multiStreamLayout' => Campaign::LAYOUT_TYPE_MULTI
                        ])
                        ->orderBy('c.amountRaised')
                        ->limit(50)
                        ->all();
    }
}