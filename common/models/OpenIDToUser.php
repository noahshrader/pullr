<?php

namespace common\models;

use yii\db\ActiveRecord;
use common\models\User;

/**
 * class is responsible for associating OpenID accounts to User model
 */
class OpenIDToUser extends ActiveRecord {

    /**
     * @return string the name of the table associated with this ActiveRecord class.
     */
    public static function tableName() {
        return 'tbl_openid_to_user';
    }

    /**
     * Check if attribute exist in service and set it for $user, if that attribute is not already set for that value
     * @param \nodge\eauth\ServiceBase $service
     * @param User $user
     * @param boolean $attributeName if attribute was changed
     */
    private static function checkAndSetAttribute($service, $user, $attributeName){
        if ($service->hasAttribute($attributeName) && ($user->$attributeName != $service->getAttribute($attributeName))) {
            $user->$attributeName = $service->getAttribute($attributeName);
            return true;
        }
        return false;
    }
    /**
     * Just set (not really save in db) common fields to $user, if they exist 
     * as attributes in $service. 
     * 
     * @param \nodge\eauth\ServiceBase $service
     * @param User $user
     * @return boolean true if at least one attribute was changed
     */
    public static function syncUser($service, $user) {
        $changed = false;
        $changed |= self::checkAndSetAttribute($service, $user, 'birthday');
        $changed |= self::checkAndSetAttribute($service, $user, 'smallPhoto');
        $changed |= self::checkAndSetAttribute($service, $user, 'photo');
       
        return $changed;
    }

    /**
     * @param \nodge\eauth\ServiceBase $service
     * @return User
     * @throws ErrorException
     */
    public static function findByEAuth($service) {
        if (!$service->getIsAuthenticated()) {
            throw new ErrorException('EAuth user should be authenticated before creating identity.');
        }

        $serviceName = $service->getServiceName();
        $id = $service->getId();
        /* $attributes = array(
          'id' => $id,
          'name' => $service->getAttribute('name'),
          'authKey' => md5($id),
          'profile' => $service->getAttributes(),
          ); */

        $openIdToUser = OpenIDToUser::find(['serviceName' => $serviceName, 'serviceId' => $id]);
        if (!$openIdToUser) {
            $user = new User();
            $user->setScenario('openId');
            $user->name = $service->getAttribute('name');
            if ($service->hasAttribute('email')) {
                $user->email = $service->getAttribute('email');
            }
            self::syncUser($service, $user);
            
            if ($user->save()) {
                $openIdToUser = new OpenIDToUser();
                $openIdToUser->serviceName = $serviceName;
                $openIdToUser->serviceId = $id;
                $openIdToUser->userId = $user->id;
                if ($service->getAttribute('url')){
                    $openIdToUser->url = $service->getAttribute('url');
                }
                $openIdToUser->save();
            }

            return $user;
        } else {
            $user = $openIdToUser->user;
            $ifChanged = self::syncUser($service, $user);
            if ($ifChanged){
                $user->setScenario('openId');
                $user->save();
            }
            return $user;
        }
    }

    /**
     * 
     * @return User
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

}
