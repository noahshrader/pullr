<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\models\User;
use common\models\Payment;
use common\components\Application;
use common\models\base\BaseImage;

/**
 * to consider account on other the base you also should check expire field to be more than current time
 */
class Charity extends ActiveRecord {

    const STATUS_ACTIVE = 'active';
    const STATUS_PENDING = 'pending';
    const STATUS_DELETED = 'deleted';

    public static $STATUSES = [self::STATUS_ACTIVE, self::STATUS_PENDING, self::STATUS_DELETED];
    public static $TYPES = ['Animals', 'Arts & Culture', 'Children & Youth', 'Community', 'Crime Prevention', 'Disabled', 'Disaster Relief',
        'Education', 'Eldery', 'Environment', 'Health & Wellness', 'Homeless', 'Human Rights', 'Hunger & Poverty', 'Overseas Aid', 'Peace',
        'Sports & Recreation', 'Substance Abuse', 'Women'];

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_charity';
    }

    public function scenarios() {
        return [
            'default' => ['name','type','status', 'paypal','url','contact', 'contactEmail', 'contactPhone', 'description']
        ];
    }
    
    public function rules() {
        return [
            ['name', 'required'],
            ['name', 'filter', 'filter' => 'strip_tags'],
            ['status', 'in', 'range' => self::$STATUSES],
            ['type', 'in', 'range' => self::$TYPES],
            ['paypal', 'email'],
            ['url', 'url', 'defaultScheme' => 'http'],
            ['contact', 'filter', 'filter' => 'strip_tags'],
            ['contactEmail', 'email'],
            ['contactPhone', 'filter', 'filter' => 'strip_tags'],
            ['phone', 'filter', 'filter' => 'strip_tags'],
            ['description', 'filter', 'filter' => 'strip_tags'],
        ];
    }
    
    /**
     * that field is used to upload photo for user avatar
     * @var type 
     */
    public $images;
    public $photo;
    public $smallPhoto;
    
    public function afterFind() {
        parent::afterFind();
        \common\components\UploadImage::ApplyLogo($this);
    }

    /**
     * 
     * @return User
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

}
