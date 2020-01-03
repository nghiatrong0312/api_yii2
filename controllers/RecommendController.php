<?php

namespace app\controllers;

use yii;
use app\models\MasterUpdate;
use app\resouce\AlertRecommend;

class RecommendController extends BaseController
{
    public function actionIndex()
    {
    	$response    =  Yii::$app->response;
        $request     =  Yii::$app->request;

    	$time        =  date("Y-m-d h:i:s", $request->get('time'));
    	$time_model  =	MasterUpdate::find()->one();
    	$time_len    =  strlen($request->get('time'));
    	// 1578020172 more then
    	// 1577958621 less then
        if ($time_len != 10) {
        	$response->statusCode = 400;
	        $response->data = array(
	             "errors"  =>  array(
	             	'time' => 'Time format invalid.',
	         	),
	       	);

	        return $response;

        }
        elseif ($time < $time_model['updateTime']) {

        	$data_recommend = AlertRecommend::find()->all();

        	return array(
        		'data' => $data_recommend,
        	);

        }
        elseif ($time > $time_model['updateTime']) {
        	
        	return array(
        		'data' => array(),
        	);

        }
        else{

        	$response->statusCode = 500;
	        $response->data       = array(
	             "error" =>  "An error occured",
	            );

	        return $response;

        }
    }

}
