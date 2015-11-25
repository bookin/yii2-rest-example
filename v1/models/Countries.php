<?php

namespace app\modules\v1\models;

use Yii;

/**
 * This is the model class for collection "countries".
 *
 * @property \MongoId|string $_id
 * @property integer $country_id
 * @property string $code
 * @property string $name
 */
class Countries extends \yii\mongodb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'countries';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            'country_id',
            'code',
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
            'country_id',
            'code',
            'name'
        ];
    }
}
