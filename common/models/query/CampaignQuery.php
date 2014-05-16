<?php
namespace common\models\query;

use yii\db\ActiveQuery;
use common\models\Campaign;

class CampaignQuery extends ActiveQuery
{
    /**
     * 
     * @return Event
     */
    public function active(){
        $campaignsQuery = $this->andWhere([
            'status' => Campaign::STATUS_ACTIVE])
                ->andWhere('startDate > '. time())
                ->andWhere('endDate < ' . time());
        return $campaignsQuery;
    }
}