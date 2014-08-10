<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 23.07.14
 * Time: 14:44
 */

namespace common\models;

use common\components\FirstGivingPayment;
use yii\db\ActiveRecord;
use SimpleXMLElement;

class FirstGiving extends ActiveRecord {

    public static function tableName() {
        return 'tbl_first_giving';
    }

    /**
     * Create First Giving Charity from XML object
     *
     * @param SimpleXMLElement $xmlItem
     * @param String $organization_uuid
     * @return FirstGiving $fg
     */
    public static function getFromXmlItem(SimpleXMLElement $xmlItem, $organization_uuid = null) {
        $fg = FirstGiving::findOne(['organization_uuid' => $organization_uuid]) ? : new FirstGiving();

        if(!$xmlItem || !$fg) {
            return false;
        }

        $fg->organization_uuid = (string) $xmlItem->organization_uuid;
        $fg->organization_name = (string) $xmlItem->organization_name;
        $fg->organization_alias = (string) $xmlItem->organization_alias ? : NULL;
        $fg->government_id = (string) $xmlItem->government_id ? : NULL;
        $fg->address_line_1 = (string) $xmlItem->address_line_1 ? : NULL;
        $fg->address_line_2 = (string) $xmlItem->address_line_2 ? : NULL;
        $fg->address_line_3 = (string) $xmlItem->address_line_3 ? : NULL;
        $fg->address_line_full = (string) $xmlItem->address_line_full ? : NULL;
        $fg->city = (string) $xmlItem->city ? : NULL;
        $fg->region = (string) $xmlItem->region ? : NULL;
        $fg->postal_code = (string) $xmlItem->postal_code ? : NULL;
        $fg->address_full = (string) $xmlItem->address_full ? : NULL;
        $fg->phone_number = (string) $xmlItem->phone_number ? : NULL;
        $fg->url = (string) $xmlItem->url ? : NULL;
        $fg->category_code = (string) $xmlItem->category_code ? : NULL;
        $fg->latitude = (string) $xmlItem->latitude ? : NULL;
        $fg->longitude = (string) $xmlItem->longitude ? : NULL;
        $fg->revoked = (string) $xmlItem->revoked ? : NULL;

        return $fg;
    }

    public static function getFromAPI($organization_uuid = null) {
        $responseString = FirstGivingPayment::sendCurlRequest('http://graphapi.firstgiving.com/v1/object/organization/' . $organization_uuid);

        if ($responseString) {
            $xmlDocument = simplexml_load_string($responseString);

            //new or updated First Giving Charity
            $firstGivingCharity = FirstGiving::getFromXmlItem($xmlDocument->children()->children(), $organization_uuid);

            if ($firstGivingCharity->save()) {

                //add or update same pullr's Charity
                $charity = Charity::findOne(['firstGivingId' => $firstGivingCharity->id]) ? : new Charity();

                $charity->firstGivingId = $firstGivingCharity->id;
                $charity->name = $firstGivingCharity->organization_name;
                $charity->status = Charity::STATUS_ACTIVE;
                //$charity->type = //????????
                $charity->url = $firstGivingCharity->url ? : '';
                $charity->contactPhone = $firstGivingCharity->phone_number;

                if ($charity->save()) {
                    return $firstGivingCharity;
                }
            }
        }

        return false;
    }

    public function getCharity() {
        return $this->hasOne(Charity::className(), ['firstGivingId' => 'id']);
    }
}
