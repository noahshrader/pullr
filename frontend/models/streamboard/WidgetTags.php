<?php

namespace frontend\models\streamboard;

use yii\db\ActiveRecord;
use common\models\User;


/**
 * @package frontend\models\streamboard
 * @property integer $userId
 * @property integer $regionNumber - either 1 / 2
 * @property boolean $lastFollower
 * @property boolean $lastSubscriber
 * @property boolean $lastDonor
 * @property boolean $largestDonation
 * @property boolean $lastDonorAndDonation
 * @property boolean $topDonor
 * @property widgetTagStyle $widgetTagStyle
 */
class WidgetTags extends ActiveRecord {


    const TAGTYPE_LAST_FOLLOWERS = 'lastfollower';
    const TAGTYPE_LAST_SUBSCRIBER = 'lastsubscriber';
    const TAGTYPE_LAST_DONNOR = 'lastdonor';
    const TAGTYPE_LARGEST_DONATION = 'largestdonation';
    const TAGTYPE_LARGEST_DONNORADNDONATION = 'lastdonoranddonation';
    const TAGTYPE_LARGEST_TOPDONNOR = 'topdonor';


    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_streamboard_widget_tags';
    }

    public function scenarios() {
        return [
            'default' => ['userId', 'regionNumber','fontStyle', 'fontSize', 'fontWeight', 'fontColor','fontUppercase','textShadow','lastFollower','lastSubscriber','lastDonor','largestDonation','lastDonorAndDonation','topDonor']
        ];
    }

    public function fields(){
        return ['userId', 'regionNumber','fontStyle', 'fontSize', 'fontWeight', 'fontColor','fontUppercase','textShadow','lastFollower','lastSubscriber','lastDonor','largestDonation','lastDonorAndDonation','topDonor',
                    'lastFollowerWidget','lastSubscriberWidget','lastDonorWidget','largestDonationWidget','lastDonorAndDonationWidget','topDonorWidget'];
    }

    public function getLastFollowerWidget(){
        return $this->hasOne(WidgetTagStyle::className(), ['userId' => 'userId', 'regionNumber' => 'regionNumber'])->andWhere(['tagType' => self::TAGTYPE_LAST_FOLLOWERS]);
    }

    public function getLastSubscriberWidget(){
        return $this->hasOne(WidgetTagStyle::className(), ['userId' => 'userId', 'regionNumber' => 'regionNumber'])->andWhere(['tagType' => self::TAGTYPE_LAST_SUBSCRIBER]);
    }

    public function getLastDonorWidget(){
        return $this->hasOne(WidgetTagStyle::className(), ['userId' => 'userId', 'regionNumber' => 'regionNumber'])->andWhere(['tagType' => self::TAGTYPE_LAST_DONNOR]);
    }

    public function getLargestDonationWidget(){
        return $this->hasOne(WidgetTagStyle::className(), ['userId' => 'userId', 'regionNumber' => 'regionNumber'])->andWhere(['tagType' => self::TAGTYPE_LARGEST_DONATION]);
    }

    public function getLastDonorAndDonationWidget(){
        return $this->hasOne(WidgetTagStyle::className(), ['userId' => 'userId', 'regionNumber' => 'regionNumber'])->andWhere(['tagType' => self::TAGTYPE_LARGEST_DONNORADNDONATION]);
    }

    public function getTopDonorWidget(){
        return $this->hasOne(WidgetTagStyle::className(), ['userId' => 'userId', 'regionNumber' => 'regionNumber'])->andWhere(['tagType' => self::TAGTYPE_LARGEST_TOPDONNOR]);
    }

    public function updateFromArray($data){
        return $this->load($data, '') && $this->save() &&
        $this->lastFollowerWidget->load($data,'lastFollowerWidget') &&
        $this->lastFollowerWidget->save() &&
        $this->lastSubscriberWidget->load($data,'lastSubscriberWidget') &&
        $this->lastSubscriberWidget->save() &&
        $this->lastDonorWidget->load($data,'lastDonorWidget') &&
        $this->lastDonorWidget->save()&&
        $this->largestDonationWidget->load($data,'largestDonationWidget') &&
        $this->largestDonationWidget->save() &&
        $this->lastDonorAndDonationWidget->load($data,'lastDonorAndDonationWidget') &&
        $this->lastDonorAndDonationWidget->save() &&
        $this->topDonorWidget->load($data,'topDonorWidget') &&
        $this->topDonorWidget->save();
    }

    public function afterSave($insert, $params = array())
    {
        parent::afterSave($insert, $params);
        if ($insert) {
            $module = new WidgetTagStyle();
            $module->tagType = self::TAGTYPE_LAST_FOLLOWERS;
            $module->userId = $this->userId;
            $module->regionNumber = $this->regionNumber;
            $module->messagePositionY = 0;
            $module->save();

            $module = new WidgetTagStyle();
            $module->tagType = self::TAGTYPE_LAST_SUBSCRIBER;
            $module->userId = $this->userId;
            $module->regionNumber = $this->regionNumber;
            $module->messagePositionY = 30;
            $module->save();

            $module = new WidgetTagStyle();
            $module->tagType = self::TAGTYPE_LAST_DONNOR;
            $module->userId = $this->userId;
            $module->regionNumber = $this->regionNumber;
            $module->messagePositionY = 60;
            $module->save();

            $module = new WidgetTagStyle();
            $module->tagType = self::TAGTYPE_LARGEST_DONATION;
            $module->userId = $this->userId;
            $module->regionNumber = $this->regionNumber;
            $module->messagePositionY = 120;
            $module->save();

            $module = new WidgetTagStyle();
            $module->tagType = self::TAGTYPE_LARGEST_DONNORADNDONATION;
            $module->userId = $this->userId;
            $module->regionNumber = $this->regionNumber;
            $module->messagePositionY = 90;
            $module->save();

            $module = new WidgetTagStyle();
            $module->tagType = self::TAGTYPE_LARGEST_TOPDONNOR;
            $module->userId = $this->userId;
            $module->regionNumber = $this->regionNumber;
            $module->messagePositionY = 150;
            $module->save();
        }
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true){
        $data = parent::toArray($fields, $expand, $recursive);
        /*as 1 and true in angular are not equal for checkbox, so let's pass true/false values*/
        $data['lastFollower'] = $this->lastFollower == 1;
        $data['lastSubscriber'] = $this->lastSubscriber == 1;
        $data['lastDonor'] = $this->lastDonor == 1;
        $data['largestDonation'] = $this->largestDonation == 1;
        $data['lastDonorAndDonation'] = $this->lastDonorAndDonation == 1;
        $data['topDonor'] = $this->topDonor == 1;
        $data['fontUppercase'] = $this->fontUppercase == 1;
        $data['textShadow'] = $this->textShadow == 1;
        return $data;
    }
}
