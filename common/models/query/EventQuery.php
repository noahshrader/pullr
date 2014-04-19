<?php
namespace common\models\query;

use yii\db\ActiveQuery;
use common\models\Event;

class EventQuery extends ActiveQuery
{
    /**
     * 
     * @return Event
     */
    public function active(){
        $event = $this->andWhere([
            'status' => Event::STATUS_ACTIVE])
                ->andWhere('startDate > '. time())
                ->andWhere('endDate < ' . time());
        return $event;
    }
}