<?php

namespace app\modules\v1\controllers;

use app\models\Errors;
use app\models\User;
use app\modules\v1\components\Controller;
use \Yii;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class UserController extends Controller
{
    public $modelClass = 'app\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'login'  => ['post']
            ],
        ];
        return $behaviors;
    }

    public function actionLogin()
    {
        $post = Yii::$app->request->post();
        
        if(empty($post)){
            throw new NotFoundHttpException('Need send username and password');
        }
        /** @var User $model */
        $model = User::find()->where(["username" => $post["username"]])->one();
        if (empty($model)) {
            throw new ForbiddenHttpException(Errors::getError(Errors::USER_WRONG_PASSWORD), Errors::USER_WRONG_PASSWORD);
        }
        if ($model->validatePassword($post["password"])) {
//            $model->last_login = Yii::$app->formatter->asTimestamp(date_create());
//            $model->save(false);
            return ['auth_key'=>$model->getAccessToken()];
        } else {
            throw new ForbiddenHttpException(Errors::getError(Errors::USER_WRONG_PASSWORD), Errors::USER_WRONG_PASSWORD);
        }
    }

    public function actionCheck(){
        return ['status'=>'ok'];
    }
}
