<?php

namespace app\controllers;
use yii;
use app\models\User;
use yii\filters\auth\HttpBasicAuth;


class BaseController extends \yii\web\Controller
{
    
    public $enableCsrfValidation = false;

	public function behaviors()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $behaviors =  parent::behaviors();
        $code 	   =  Yii::$app->request->getAuthUser();
    	$password  =  Yii::$app->request->getAuthPassword();
    	if (isset($code)) {
    	 	$user_data = User::find()->where(['userCode' => $code])->one();
    	 	
    	 	if (password_verify($password, $user_data['password'])) {
                return $behaviors;
            }
    	}
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
        ];
       	
       	return $behaviors;
    }

}