<?php

namespace app\controllers;

use yii;
use app\models\User;
use app\models\Organization;
class AuthController extends BaseController
{

	public $enableCsrfValidation = false;

    public function actionAuth()
    {

    	$code 		=  Yii::$app->request->post('code');
    	$password 	=  Yii::$app->request->post('password');
    	$response 	=  Yii::$app->response;

    	if (isset($code)) {
    		$user_model = User::find()->where(['userCode' => $code])->one();
    		if ($user_model['attemptCount'] > 3) {
    			$response->statusCode = 401;
                $response->data = array(
                    "error" => array(
                        "userCode" => "User code was Loked",
                    ),
                );
                return $response;
    		}
    		if (password_verify($password, $user_model['password']) && $user_model['kyotenType'] == 5) {

    			$user_model->attemptCount = 0;
    			$user_model->save();

    			$level4 = Organization::find()->where(['organization.id' => $user_model['id']])->one();
    			$level3 = Organization::find()->where(['organization.id' => $level4->parentId])->one();
    			$level2 = Organization::find()->where(['organization.id' => $level3->parentId])->one();
    			$level1 = Organization::find()->where(['organization.id' => $level2->parentId])->one();

    			return $data = array(
                    "id" => $user_model['id'],
                    "kyotenType" => $user_model['kyotenType'],
                    'listOrganizationId' => array([
                        array(
                            'id' => $user_model['id'],
                            'organizationLevelId' => 5,
                        ),
                        array(
                            'id' => $level4->parentId,
                            'organizationLevelId' => 4,
                        ),
                        array(
                            'id' => $level3->parentId,
                            'organizationLevelId' => 3,
                        ),
                        array(
                            'id' => $level2->parentId,
                            'organizationLevelId' => 2,
                        ),
                        array(
                            'id' => $level1->parentId,
                            'organizationLevelId' => 1,
                        ),
                    ]),
                );
    		}
    		elseif ($user_model === null) {

    			$response->statusCode = 401;
                $response->data = array(
                    "error" => array(
                        "userCode" => "User code not exits",
                    ),
                );
                return $response;
    		}
    		elseif(password_verify($password, $user_model['password']) && $user_model['kyotenType'] != 5) {

    			$user_model->attemptCount = $user_model['attemptCount'] + 1;
    			$user_model->save();

                $response->statusCode = 401;
                $response->data = array(
                    "error" => array(
                        "userCode" => "User code not exits",
                    ),
                );
                return $response;
    		}else {
    			$user_model->attemptCount = $user_model['attemptCount'] + 1;
    			$user_model->save();

                $response->statusCode = 401;
                $response->data = array(
                    "error" => array(
                        "userCode" => "User code not exits",
                    ),
                );
                return $response;
    		}
    	}
    	$response->statusCode = 401;
        $response->data = array(            
                "error" => "An error occured !",
        );
        return $response;
    }

}
