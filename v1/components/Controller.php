<?php

namespace app\modules\v1\components;


use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;

class Controller extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'except' => ['login','error'],
            'authMethods' => [
                HttpBearerAuth::className(),
            ],
        ];
        unset($behaviors['rateLimiter']);
        return $behaviors;
    }

    public function actionError(){
        throw new ForbiddenHttpException();
    }

}