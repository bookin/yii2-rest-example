<?php
/**
 * Created by PhpStorm.
 * User: Bookin
 * Date: 05.11.2015
 * Time: 23:09
 */

namespace app\modules\v1\controllers;


use app\modules\v1\components\Controller;
use app\modules\v1\models\Cities;
use app\modules\v1\models\Countries;
use app\modules\v1\models\Proxy;
use yii\data\ActiveDataProvider;
use yii\filters\RateLimiter;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class ProxyController extends Controller
{
    public $modelClass = 'app\modules\v1\models\Proxy';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['rateLimiter']=[
            'class' => RateLimiter::className(),
            'only' => ['view']
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'view'  => ['get'],
                'countries'  => ['get'],
                'cities'  => ['get']
            ],
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['view'], $actions['index']);
        return $actions;
    }

    public function actionIndex(){
        /*
            TODO - Добавить сортировки
            @author - Bookin
            @date - 12.11.2015
            @time - 22:15
        */
        
        $queries = \Yii::$app->request->getQueryParams();
        $where = [];
        if($queries){
            $allowed = [
                'country_id',
                'city_id',
                'get',
                'post',
                'cookie',
                'referer',
                'user_agent',
                'proxy_level'
            ];
            $queries = array_intersect_key($queries, array_flip($allowed));
            foreach($queries as $key=>$value){
                switch($key){
                    case 'country_id':
                        $where['country_id'] = (int)$value;
                        break;
                    case 'city_id':
                        $where['city_id'] = (int)$value;
                        break;
                    case 'get':
                    case 'post':
                    case 'cookie':
                    case 'referer':
                    case 'user_agent':
                        $where['allowed']['$in'][] = $value;
                        break;
                    case 'proxy_level':
                        $where['proxy_level'] = $value;
                        break;

                }
            }
        }
        $query = Proxy::find()->where($where);
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function actionView($id)
    {
        try {
            $check = new \MongoId($id);
        } catch (\MongoException $ex) {
            throw new NotFoundHttpException();
        }

        $model = Proxy::find()->where(['_id'=>new \MongoId($id)])->one();
        if(!$model){
            throw new NotFoundHttpException();
        }
        $response = ['id'=>$id, 'ip'=>$model->ip, 'port'=>$model->port] + ArrayHelper::toArray($model);
        return $response;
    }

    public function actionCountries(){
        $countries = Countries::find()->all();
        $result = [];
        if($countries){
            /** @var Countries $country */
            foreach($countries as $country){
                $result[]=ArrayHelper::toArray($country);
            }
        }
        return json_encode($result);
    }

    public function actionCities(){
        $country_id = (int)\Yii::$app->request->getQueryParam('country_id');
        if($country_id){
            $cities = Cities::find()->where(['country_id'=>$country_id])->all();
        }else{
            $cities = Cities::find()->all();
        }
        $result = [];
        if($cities){
            /** @var Cities $city */
            foreach($cities as $city){
                $result[]=ArrayHelper::toArray($city);
            }
        }
        return json_encode($result);
    }
}