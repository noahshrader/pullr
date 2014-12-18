<?
namespace common\models;

use common\models\User;
use common\models\Campaign;
use yii\db\ActiveRecord;

class FeaturedCampaign extends ActiveRecord {

    public static function tableName()
    {
        return 'tbl_featured_campaign';
    }

    public function scanarios()
    {
        return [
            'default' => ['userId', 'campaignId']
        ];
    }


    public static function setFeaturedCampaignFromArray(User $user, array $campaignIds)
    {
        //delete old user feature campaign
        \Yii::$app->db->createCommand()->delete(FeaturedCampaign::tableName(), 'userId = :userId', ['userId' => $user->id])->execute();

        //batch insert feature campaign
        if ( ! empty($campaignIds)) {
            $insertData = [];
            foreach ($campaignIds as $campaignId) {
                $insertData[] = [$user->id, $campaignId];
            }
            \Yii::$app->db->createCommand()->batchInsert(FeaturedCampaign::tableName(), ['userId', 'campaignId'], $insertData)->execute();
        }

    }

    public static function getFeaturedCampaign($user)
    {

    }
}