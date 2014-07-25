<?php

use yii\db\Schema;
use yii\db\Migration;
use common\models\FirstGiving;

/**
 * Create FirstGiving table
 *
 * @author Sergey Bulychev <svbulychev@gmail.com>
 * @since 23.07.2014
*/
class m140723_083930_first_giving_table extends Migration
{
    public function up()
    {
        $this->createTable(FirstGiving::tableName(), [
            'id' => Schema::TYPE_PK,
            'organization_uuid' => Schema::TYPE_STRING . ' NOT NULL',
            'organization_name' => Schema::TYPE_STRING . ' NOT NULL',
            'organization_alias' => Schema::TYPE_STRING,
            'government_id' => Schema::TYPE_INTEGER,
            'address_line_1' => Schema::TYPE_STRING,
            'address_line_2' => Schema::TYPE_STRING,
            'address_line_3' => Schema::TYPE_STRING,
            'address_line_full' => Schema::TYPE_STRING,
            'city' => Schema::TYPE_STRING,
            'region' => Schema::TYPE_STRING,
            'postal_code' => Schema::TYPE_STRING,
            'country' => Schema::TYPE_STRING,
            'address_full' => Schema::TYPE_STRING,
            'phone_number' => Schema::TYPE_STRING,
            'url' => Schema::TYPE_STRING,
            'category_code' => Schema::TYPE_STRING,
            'latitude' => Schema::TYPE_STRING,
            'longitude' => Schema::TYPE_STRING,
            'revoked' => Schema::TYPE_BOOLEAN,
        ]);

        $this->sampleData();
    }

    public function sampleData() {

        echo "Getting data from FirstGiving. Please wait ... \n";

        /**
         * @todo HttpRequest instead file_get_contents
         */
        $request = new HttpRequest('http://graphapi.firstgiving.com/v1/list/organization?q=organization_name:*AMERICAN*');
        try {
            $request->send();
            if ($request->getResponseCode() == 200) {
                $xmlDocument = simplexml_load_string($request->getResponseBody());

                foreach($xmlDocument->children()->children() as $xmlItem) {
                    $fg = new FirstGiving();

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

                    $fg->save();
                }
            }
        } catch (HttpException $ex) {
            echo $ex;
        }
    }

    public function down()
    {
        $this->dropTable(FirstGiving::tableName());
    }
}
