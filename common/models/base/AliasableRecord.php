<?php

namespace common\models\base;

use yii\db\ActiveRecord;

/**
 * To use validation add   ['alias', 'filter', 'filter' => [$this, 'validateAlias']],
 * after all [name] and [status] filtering. 
 * 
 * Descendant records should contain [name],[alias] as table column.
 * And [id] as primary key if the record already exist. 
 * [name] column will be used to generate [alias] if [alias] is not setted.
 * 
 * [alias] length can be from 2 to 70. (VARCHAR(70) for mysql). 
 * 
 * If record contain [status] as attribute [aliasStatusesToCheck] method will be used as list of statuses to 
 * check same alias existing only if they are in the same status
 * 
 *If record contain [cityId] as attribute it will check is alias is taken only that city 
 */
class AliasableRecord extends ActiveRecord {

    const MIN_ALIAS_LENGTH = 3;
    const MAX_ALIAS_LENGTH = 70;

    
    const STATUS_ACTIVE = 'active';
    const STATUS_PENDING = 'pending';
    const STATUS_DELETED = 'deleted';
    
    public function validateAlias($alias) {
        $alias = $this->filterAlias($alias);


        if (!$alias) {
            $alias = $this->generateAlias();
            if (!$this->checkAliasAvailability($alias, $error)) {
                $i = 1;
                do {
                    $i++;
                } while (!$this->checkAliasAvailability($alias . '_' . $i, $error));
                $alias.='_' . $i;
            }
        } else {
            if (!$this->checkAliasAvailability($alias,$error)) {
                $this->addError('alias', $error);
            }
        }
        return $alias;
    }

    public function filterAlias($alias) {
        $alias = mb_strtolower($alias);
        $alias = strip_tags($alias);
        $alias = preg_replace('/[\s\'\"&,_#]+/', '_', $alias);
        $alias = trim($alias, '_');
        /**
         * with -6 we have up to 99999 postfixes like generated "name_99999"
         */
        $alias = substr($alias, 0, static::MAX_ALIAS_LENGTH-6);
        $alias = trim($alias, '_');
        return $alias;
    }

    public function generateAlias() {
        return $this->filterAlias($this->name);
    }

    public function aliasStatusesToCheck(){
        return [static::STATUS_PENDING, static::STATUS_ACTIVE];
    }
    /** check if alias is taken on any not deleted city */
    public function checkAliasAvailability($alias,&$error) {
        $length = strlen($alias);
        if ($length < static::MIN_ALIAS_LENGTH || $length > static::MAX_ALIAS_LENGTH) {
            $error = 'Alias length should be from '.static::MIN_ALIAS_LENGTH.' to '.static::MAX_ALIAS_LENGTH.'.';
            return false;
        }

        $query = static::find()->where(['alias' => $alias]);
        if ($this->id) {
            $query->andWhere('id <> ' . $this->id);
        }
        
        if ($this->hasAttribute('status')){
            $query->andWhere(['status' => $this->aliasStatusesToCheck()]);
        }
        
        if ($this->hasAttribute('cityId')){
            $query->andWhere(['cityId' => $this->cityId]);
        }
        if ($query->count() != 0){
            $error = 'Alias already taken.';
            return false;
        }
        return true;
    }

}
