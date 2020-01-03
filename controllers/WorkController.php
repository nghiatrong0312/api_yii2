<?php

namespace app\controllers;

use yii;
use yii\db\Query;
use app\models\Work;
use app\models\User;
use app\models\Logs;
use app\models\SelfCheckTask;
use app\models\OfficeCheckTask;
use app\models\Image;
use app\models\Audio;


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
                    $response->statusCode = 400;
                    $response->data = array(
                         "error" => array(
                             "userCode" => "Value of userCode invalid",
                         ),
                    );
                    return $response;
                }

                if ($works['status'] === 6) {
                    $response->statusCode = 400;
                    $response->data = array(
                        "error" => array(
                            "userCode" => "successful job !",
                        ),
                    );
                    return $response;
                }

                foreach ($dif as $key => $value) {
                   if (isset($value)) {
                        $response->statusCode = 400;
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
                        $response->statusCode = 400;
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
                    $response->statusCode = 400;
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
            $response->statusCode = 400;
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

    public function actionCheck()
    {
        $response  = Yii::$app->response;
        $request   = Yii::$app->request;
        
        $authUser  = $request->getAuthUser();
        $images    = $request->post('images');

        $works     = Work::findOne([
                        'userCode' => $authUser,
                        'id'       => $request->get('workId'),
                    ]);

        if ($works !== null) {

            if ($request->post('workId') !=  $request->get('workId')) {

                $response->statusCode = 400;
                $response->data = array(
                    "errors" => '',
                );
                return $response;
            }

            if (!empty($request->post('workTypeId'))) {

                // if ($works['status'] == 2) {
                //     $response->statusCode = 400;
                //     $response->data = array(
                //         "errors" => 'The work is starting !',
                //     );
                //     return $response;
                // }

                $self_check               =  new SelfCheckTask();
                $self_check->userCode     =  $authUser; 
                $self_check->workId       =  $request->post('workId');   
                $self_check->state        =  123; 
                $self_check->workTypeId   =  $request->post('workTypeId');  
                $self_check->isAutomatic  =  $request->post('isAutomatic');  
                $self_check->save(false);

                $works->status            =  2;
                $works->start             =  date("Y-m-d H:i:s");
                $works->checkType         =  $request->post('checkType');
                $works->powerAIImage      =  $request->post('powerAIImage');
                $works->save();

                $audio_model              =  new Audio();
                $audio_model->checkType   =  $request->post('checkType');
                $audio_model->taskId      =  $request->post('workId');
                $audio_model->fileCode    =  $request->post('audioFileName');
                $audio_model->fileName    =  $request->post('audioFileName');
                $audio_model->save(false);



                foreach ($images as $key => $image) {
                    $img_model                         =   new Image();
                    $img_model->taskId                 =   $request->post('workId');
                    $img_model->checkType              =   $request->post('checkType');
                    $img_model->checkItemId            =   $image['checkItemId'];
                    $img_model->fileCode               =   $image['fileName'];
                    $img_model->fileName               =   $image['fileName'];
                    $img_model->isConfirmed            =   123;
                    $img_model->objectRecognizeResult  =   $image['isObjectRecognizeSuccess'];
                    $img_model->save(false);
                }

                return $works;

            }
            elseif (!empty($request->post('confirmTypeId'))) {

                // if ($works['status'] == 3) {
                //     $response->statusCode = 400;
                //     $response->data = array(
                //         "errors" => 'The work is starting !',
                //     );
                //     return $response;
                // }

                $office_check                 =  new OfficeCheckTask();
                $office_check->userCode       =  $authUser; 
                $office_check->workId         =  $request->post('workId');   
                $office_check->comment        =  $request->post('comment');
                $office_check->state          =  123; 
                $office_check->confirmTypeId  =  $request->post('confirmTypeId');    
                $office_check->save(false);

                $works->status                =  3;
                $works->start                 =  '';
                $works->checkType             =  $request->post('checkType');
                $works->save();

                $audio_model                  =  new Audio();
                $audio_model->checkType       =  $request->post('checkType');
                $audio_model->taskId          =  $request->post('workId');
                $audio_model->fileCode        =  $request->post('audioFileName');
                $audio_model->fileName        =  $request->post('audioFileName');
                $audio_model->save(false);

                foreach ($images as $key => $image) {
                    $img_model                         =   new Image();
                    $img_model->taskId                 =   $request->post('workId');
                    $img_model->checkType              =   $request->post('checkType');
                    $img_model->checkItemId            =   $image['checkItemId'];
                    $img_model->fileCode               =   $image['fileName'];
                    $img_model->fileName               =   $image['fileName'];
                    $img_model->isConfirmed            =   123;
                    $img_model->objectRecognizeResult  =   $image['isObjectRecognizeSuccess'];
                    $img_model->save(false);
                }

                return $works;
            }
            else{
                $response->statusCode = 400;
                $response->data = array(
                    "errors" => '',
                );
                return $response;
            }

        }
        elseif ($works === null) {
            $response->statusCode = 400;
            $response->data = array(
                "errors" => '',
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
