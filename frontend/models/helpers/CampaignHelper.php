<?
namespace frontend\models\helpers;

use common\models\Donation;
use common\models\Campaign;
use common\models\User;
use frontend\models\streamboard\StreamboardConfig;
use yii\db\Query;

class CampaignHelper {
    public static function getCharityCampaigns()
    {
        return Campaign::find()->where(['type' => Campaign::TYPE_CHARITY_FUNDRAISER])
                                ->andWhere(['status' => Campaign::STATUS_ACTIVE])
                                ->all();
    }

    public static function getFeaturedCampaigns()
    {

        return Campaign::find()
                        ->from(Campaign::tableName() . ' c')
                        ->leftJoin(StreamboardConfig::tableName() . ' sc',  'sc.featuredCampaignId = c.id')
                        ->leftJoin(User::tableName() . ' u', 'u.id = c.userId')
                        ->where(['c.status' => Campaign::STATUS_ACTIVE])
                        ->andWhere([
                            'u.status' => User::STATUS_ACTIVE,
                            'sc.enableFeaturedCampaign' => 1
                        ])
                        ->andWhere('(c.layoutType= :singleStreamLayout and c.channelName != "") or (c.layoutType = :teamLayout and c.channelTeam !="") or (c.layoutType = :multiStreamLayout)')
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