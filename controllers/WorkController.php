<?php

namespace app\controllers;

use yii;
use yii\db\Query;
use app\models\Work;
use app\models\User;
use app\models\Logs;


class WorkController extends BaseController
{
    public function actionIndex()
    {

        $query    = new Query;
        $response = Yii::$app->response;
        $userCode = Yii::$app->request->get('userCode');

        if (isset($userCode)) {
        	$user = User::find()->where(['userCode' => $userCode])->one();
        	if (!empty($user)) {
        		$data = $query->select('work.id, code, date, officeComment, work.status')
        					->from('user')
        					->join('RIGHT JOIN', 'work', 'user.userCode = work.userCode')
        					->where(['user.userCode' => $userCode])
        					->all();

        		return array(
	        		"data" => $data,
	        	);

        	}
        	elseif ($user === null) {
        		$response->statusCode = 400;
                $response->data = array(
                    "error" => array(
                        "userCode" => "User code not exits",
                    ),
                );
                return $response;
        	}
        }
        $response->statusCode = 500;
        $response->data = array(
            "error" => array(
                "userCode" => "An error occored",
            ),
        );
        return $response;
        	
    }

    public function actionCreate()
    {
        $request  = Yii::$app->request;
        $response = Yii::$app->response;
        $userCode = Yii::$app->request->get('userCode');

        if (isset($userCode)) {
        	$user = User::find()->where(['userCode' => $userCode])->one();
        	if (!empty($user)) {
        		$data 							= 	new Work();
		        $data->userCode 				= 	$userCode;
		        $data->code 					= 	$request->post('code');
		        $data->status 					= 	1;
		        $data->date 					= 	date("Y-m-d");
		        $data->faOperationId 			= 	$request->post('faOperationId');
		        $data->faOperationKind 			= 	$request->post('faOperationKind');
		        $data->faOperationDetail 		= 	$request->post('faOperationDetail');
		        $data->faOperationDate 			= 	$request->post('faOperationDate');
		        $data->faConstructionTimePeriod = 	$request->post('faConstructionTimePeriod');
		        $data->save(false);

        		return array(
		        	"id"	 => $data['id'],
					"code"   => $data['code'],
					"date"   => $data['date'],
					"status" => $data['status'],
		        );

        	}
        	elseif ($user === null) {
        		$response->statusCode = 400;
                $response->data = array(
                    "error" => array(
                        "userCode" => "User code not exits",
                        "code"	   =>  "Data input invalid",
                    ),
                );
                return $response;
        	}
        }
        $response->statusCode = 500;
        $response->data = array(
            "error" => array(
                "userCode" => "An error occored",
            ),
        );
        return $response;
    }

    public function actionFinish()
    {

        $array     = [];
        $success   = [];

        $response  = Yii::$app->response;
        $request   = Yii::$app->request;
        
        $authUser  = $request->getAuthUser();
        $userCode  = $request->post('userCode');
        $datas     = $request->post('data');

        if ($userCode === $authUser) {

            $user = User::find()->where(['userCode' => $authUser])->one();

            foreach ($datas as $key => $data) {

                $array[]  =  $data['workId'];
                $works    =  Work::find()->where(['id' => $data['workId']])->one();
                $id       =  explode(' ', $data['workId']);
                $id_model =  explode(' ', $works['id']);
                $dif      =  array_diff($id, $id_model);

                

                if ($works['userCode'] !== $userCode) {
                    $response->statusCode = 401;
                    $response->data = array(
                         "error" => array(
                             "userCode" => "Value of userCode invalid",
                         ),
                    );
                    return $response;
                }

                if ($works['status'] === 6) {
                    $response->statusCode = 401;
                    $response->data = array(
                        "error" => array(
                            "userCode" => "successful job !",
                        ),
                    );
                    return $response;
                }

                foreach ($dif as $key => $value) {
                   if (isset($value)) {
                        $response->statusCode = 401;
                        $response->data = array(
                            "error" => array(
                                "userCode" => "Value of userCode invalid",
                            ),
                        );
                        return $response;
                   }
                }
                foreach ($data['logs'] as $log_data)
                {
                    if ($log_data['flow'] === '' || $log_data['workId'] === '' || $log_data['eventId'] === '' || $log_data['workDate'] === '' || $log_data['workTypeId'] === '' || $log_data['status'] === ''|| $log_data['id'] === '')
                    {
                        $response->statusCode = 401;
                        $response->data = array(
                            "error" => array(
                                "userCode" => "Value of userCode invalid",
                            ),
                        );
                        return $response;
                    }
                }
            }

            $dups = array_diff_assoc($array, array_unique($array));
            foreach ($dups as $key => $dup) {
                if (isset($dup)) {
                    $response->statusCode = 401;
                    $response->data = array(
                        "error" => array(
                            "userCode" => "sdf",
                        ),
                    );
                    return $response;
                }
            }
            

            foreach ($datas as $key => $value) {

                $success[] = array(
                    'workId' => $value['workId'],
                    "status" => true
                );

                $work_data  =  Work::find()->where(['id' => $value['workId']])->all();

                foreach ($value['logs'] as $log_data)
                {
                    $logs_model               =   new Logs();
                    $logs_model->userId       =   $user['id'];
                    $logs_model->flow         =   $log_data['flow'];
                    $logs_model->workDate     =   date("Y-m-d", $log_data['workDate']/1000);
                    $logs_model->workId       =   $log_data['workId'];
                    $logs_model->eventId      =   $log_data['eventId'];
                    $logs_model->workTypeId   =   $log_data['workTypeId'];
                    $logs_model->status       =   $log_data['status'];
                    $logs_model->save(false);

                
                }
                foreach ($work_data as $key => $work_datas) {

                    $work_datas->status = 6;
                    $work_datas->end = date("Y-m-d H:i:s", $value['end']/1000);
                    $work_datas->save();

                }
            }
            return $success;

        }else{
            $response->statusCode = 401;
            $response->data = array(
             "error" => array(
                 "userCode" => "Value of userCode invalid",
             ),
            );
            return $response;
        }
        $response->statusCode = 500;
            $response->data = array(
             "error" =>  "An error occured",
            );
            return $response;
        
    }


}
