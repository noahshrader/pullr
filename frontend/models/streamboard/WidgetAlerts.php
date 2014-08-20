<?php

namespace frontend\models\streamboard;

use yii\db\ActiveRecord;
use common\models\User;
use common\components\streamboard\alert\AlertMediaManager;

/**
 * @package frontend\models\streamboard
 * @property integer $userId
 * @property integer $regionNumber - either 1 / 2
 * @property boolean $includeFollowers
 * @property boolean $includeSubscribers
 * @property boolean $includeDonations
 * @property WidgetAlertsPreference $followersPreference
 * @property WidgetAlertsPreference $subscribersPreference
 * @property WidgetAlertsPreference $donationsPreference
 */
class WidgetAlerts extends ActiveRecord {
    const PREFERENCE_FOLLOWERS = 'followers';
    const PREFERENCE_SUBSCRIBERS = 'subscribers';
    const PREFERENCE_DONATIONS = 'donations';

    public static $PREFERENCES_TYPES = [self::PREFERENCE_FOLLOWERS, self::PREFERENCE_SUBSCRIBERS, self::PREFERENCE_DONATIONS];
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_streamboard_widget_alerts';
    }

    public function scenarios() {
        return [
            'default' => ['includeFollowers', 'includeSubscribers', 'includeDonations', 'animationDelaySeconds']
        ];
    }

    public function fields(){
        return ['userId', 'regionNumber', 'includeFollowers', 'includeSubscribers', 'includeDonations',
            'animationDelaySeconds', 'followersPreference', 'subscribersPreference', 'donationsPreference',
        ];
    }

    public function afterSave($insert, $params = array())
    {
        parent::afterSave($insert, $params);
        if ($insert) {
            /** so we have new record*/

            $followersPreference = new WidgetAlertsPreference();
            $followersPreference->userId = $this->userId;
            $followersPreference->regionNumber = $this->regionNumber;
            $followersPreference->preferenceType = self::PREFERENCE_FOLLOWERS;

            $subscribersPreference = clone $followersPreference;
            $subscribersPreference->preferenceType = self::PREFERENCE_SUBSCRIBERS;

            $donationsPreference = clone $followersPreference;
            $donationsPreference->preferenceType = self::PREFERENCE_DONATIONS;

            $followersPreference->save();
            $subscribersPreference->save();
            $donationsPreference->save();
        }
    }

    public function getFollowersPreference(){
        return $this->hasOne(WidgetAlertsPreference::className(), ['userId' => 'userId', 'regionNumber' => 'regionNumber'])->andWhere(['preferenceType' => self::PREFERENCE_FOLLOWERS]);
    }

    public function getSubscribersPreference(){
        return $this->hasOne(WidgetAlertsPreference::className(), ['userId' => 'userId', 'regionNumber' => 'regionNumber'])->andWhere(['preferenceType' => self::PREFERENCE_SUBSCRIBERS]);
    }

    public function getDonationsPreference(){
        return $this->hasOne(WidgetAlertsPreference::className(), ['userId' => 'userId', 'regionNumber' => 'regionNumber'])->andWhere(['preferenceType' => self::PREFERENCE_DONATIONS]);
    }

    public function getCustomImages(){
        return AlertMediaManager::getCustomImages();
    }

    public function getCustomSounds(){
        return AlertMediaManager::getCustomSounds();
    }

    public function getLibraryImages(){
        return AlertMediaManager::getLibraryImages();
    }

    public function getLibrarySounds(){
        return AlertMediaManager::getLibrarySounds();
    }

    public function updateFromArray($data){
        return $this->load($data, '') && $this->save() &&
        $this->followersPreference->load($data,'followersPreference') &&
        $this->followersPreference->save() &&
        $this->subscribersPreference->load($data,'subscribersPreference') &&
        $this->subscribersPreference->save() &&
        $this->donationsPreference->load($data,'donationsPreference') &&
        $this->donationsPreference->save();
    }

    public function toArray(array $fields = [], array $expand = [], $recursive = true){
        $data = parent::toArray($fields, $expand, $recursive);
        /*as 1 and true in angular are not equal for checkbox, so let's pass true/false values*/
        $data['includeFollowers'] = $this->includeFollowers == 1;
        $data['includeSubscribers'] = $this->includeSubscribers == 1;
        $data['includeDonations'] = $this->includeDonations == 1;

        return $data;
    }
}
