<?php

namespace app\modules\v1\models;

use Yii;

/**
 * This is the model class for collection "cities".
 *
 * @property \MongoId|string $_id
 * @property integer $city_id
 * @property integer $country_id
 * @property string $name
 */
class Cities extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'cities';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'city_id',
            'country_id',
            'name'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            '_id' => 'ID',
        ];
    }

    public function fields(){
        return [
            'city_id',
            'country_id',
            'name'
        ];
    }
}
