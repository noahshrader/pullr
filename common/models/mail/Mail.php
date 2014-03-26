<?php

namespace common\models\mail;

use yii\db\ActiveRecord;
use common\models\User;
use common\models\Charity;
/**
 * to consider account on other the base you also should check expire field to be more than current time
 */
class Mail extends ActiveRecord {
    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_mail';
    }

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->from = 'noreply@flaper.info';
    }
}
