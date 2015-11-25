<?php

namespace app\modules\v1\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for table "rest_limits".
 *
 * @property integer $id
 * @property string $user_id
 * @property string $proxy_limit
 * @property string $proxy_time
 */
class RestLimits extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'rest_limits';
    }

    public function attributes(){
        return [
            '_id',
            'user_id',
            'proxy_limit',
            'proxy_time'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'proxy_limit', 'proxy_time'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'proxy_limit' => 'Proxy Limit',
            'proxy_time' => 'Proxy Time',
        ];
    }

    public static function addLimit($user_id, $limit, $time){
        $model = self::find()->where(['user_id'=>$user_id])->one();
        if(!$model){
            $model = new self;
            $model->user_id = $user_id;
        }
        $model->proxy_limit = $limit;
        $model->proxy_time = $time;
        $model->save(false);
    }

    public static function getLimit($user_id){
        /** @var self $model */
        $model = self::find()->where(['user_id'=>$user_id])->one();
        if($model){
            return [$model->proxy_limit, $model->proxy_time];
        }else{
            return null;
        }
    }
}
