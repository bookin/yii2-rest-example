<?php
/**
 * Created by PhpStorm.
 * User: Bookin
 * Date: 15.11.2015
 * Time: 2:33
 */

namespace app\modules\v1\models;

use yii\data\ActiveDataProvider;

class ProxySearch extends Proxy
{
    public function rules()
    {
        return [
            [['country_id','city_id'], 'number']
        ];
    }

    public function search($params = []){
        $query = Proxy::find();

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        if($params){
            $load = $this->load($params);
            $validate = $this->validate();

            if($this->country_id)
                $query->andWhere(['country_id'=>(int)$this->country_id]);

            if($this->city_id)
                $query->andWhere(['city_id'=>(int)$this->city_id]);
        }


        return $provider;
    }

}