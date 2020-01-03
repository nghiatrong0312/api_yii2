<?php

namespace app\controllers;

use yii;


class ObjectController extends BaseController
{
    public function actionIndex()
    {

        $response        =  Yii::$app->response;
        $request         =  Yii::$app->request;

        if (isset($_FILES['object'])) {

        	$target_dir  =  '/Applications/MAMP/htdocs/api_yii2/web/uploads/'; 
        	$upload_tmp  =  $_FILES['object']['tmp_name'];
	    	$filename    =  $_FILES['object']['name'];
            $array       =  explode('.', $filename);
            $extension   =  end($array);
			$target_file =  $target_dir . $request->get('filename'). "_" . date('Y-m-d h:i:s') . "." . $extension;
	        

        	if ($extension != 'png' && $extension != 'jpg' && $extension != 'jpeg' && $extension != 'mp3' && $extension != 'mp4') {

        		return 'Sorry, only JPG, JPEG, PNG, MP3 and MP4 files are allowed.';

        	}
        	elseif (move_uploaded_file($upload_tmp, $target_file)) {

	        	return 'Upload Success !';
		    } 
		    else {

		        return 'Fail to upload !';
		    }
        }

        $response->statusCode = 500;
        $response->data = array(
             "error" =>  "An error occured",
            );
        return $response;

    }

}
