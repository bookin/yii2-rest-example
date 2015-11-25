<?php

namespace app\modules\v1\models;

use Yii;
use yii\filters\RateLimitInterface;
use yii\mongodb\ActiveRecord;

/**
 * This is the model class for table "proxy".
 *
 * @property integer $id
 * @property string $ip
 * @property string $port
 */
class Proxy extends ActiveRecord
{
    const PROXY_COUNT_LIMIT = 5;
    const PROXY_TIME_LIMIT = 60;

    const LEVEL_TRANSPARENT = 0;
    const LEVEL_ANONYMOUS = 1;
    const LEVEL_ELITE = 2;

    const METHOD_NOT_INSTALLED = null;
    const METHOD_ALLOWED = 1;
    const METHOD_DISALLOWED = -1;

    /**
     * @inheritdoc
     */
    public static function collectionName()
    {
        return 'proxy';
    }

    public function attributes(){
        return [
            '_id',
            'ip',
            'port',
            'type',
            'country_id',
            'city_id',
            'allowed',
            'disallowed',
            'proxy_level',
            'time_update'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ip'], 'string'],
            [['port'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'Ip',
            'port' => 'Port',
        ];
    }

    /**
     * @inheritdoc
     * @return ProxyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProxyQuery(get_called_class());
    }

    public function fields()
    {
        return [
            'id'=>function(){
                return (string)$this->primaryKey;
            },
            'name' => function () {
                $mask = explode('.',$this->ip);
                $mask[2] = $mask[3] = '';
                return implode('.',$mask).'.';
            },
            'type',
            'country_id',
            'city_id',
            'get'=>function(){
                return $this->getStatusMethod('get');
            },
            'post'=>function(){
                return $this->getStatusMethod('post');
            },
            'cookie'=>function(){
                return $this->getStatusMethod('cookie');
            },
            'referer'=>function(){
                return $this->getStatusMethod('referer');
            },
            'user_agent'=>function(){
                return $this->getStatusMethod('user_agent');
            },
            'proxy_level',
            'last_check'=>function(){
                return $this->time_update;
            }
        ];
    }

    public function getStatusMethod($key){
        $allowed = $this->allowed;
        $disallowed = $this->disallowed;

        if(empty($allowed) && empty($disallowed)){
            return self::METHOD_NOT_INSTALLED;
        }else if(in_array($key,$allowed)){
            return self::METHOD_ALLOWED;
        }else if(in_array($key,$disallowed)){
            return self::METHOD_DISALLOWED;
        }else{
            return self::METHOD_NOT_INSTALLED;
        }
    }

    public function getCountry(){
        return $this->hasOne(Countries::className(), ['country_id'=>'country_id']);
    }

    public function getCity(){
        return $this->hasOne(Cities::className(), ['city_id'=>'city_id']);
    }
}
