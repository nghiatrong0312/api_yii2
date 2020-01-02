<?php

namespace app\controllers;

use yii;

use yii\helpers\Url;

class ObjectController extends BaseController
{
    public function actionIndex()
    {

        $response        =  Yii::$app->response;
        $request         =  Yii::$app->request;

        if (isset($_FILES['images'])) {

        	$upload_tmp_img  =  $_FILES['images']['tmp_name'];
	    	$filename_img    =  $_FILES['images']['name'];
	    	$target_dir_img  =  '/Applications/MAMP/htdocs/api_yii2/web/uploads/images/'; 
			$target_file_img =  $target_dir_img . basename($filename_img);
	        $array 		     =  explode('.', $filename_img);
			$extension_img   =  end($array);


			if ($request->get('filename') != $filename_img ) {

        		return 'error';
        		
        	}
        	elseif ($extension_img != 'png' && $extension_img != 'jpg' && $extension_img != 'jpeg') {

        		return 'Sorry, only JPG, JPEG, PNG files are allowed.';

        	}
        	elseif (move_uploaded_file($upload_tmp_img, $target_file_img)) {

	        	return 'Upload Success !';
		    } 
		    else {

		        return 'Fail to upload !';
		    }
        }

        if (isset($_FILES['audio'])) {

        	$upload_tmp_audio   =  $_FILES['audio']['tmp_name'];
	    	$filename_audio     =  $_FILES['audio']['name'];
	    	$target_dir_audio   =  '/Applications/MAMP/htdocs/api_yii2/web/uploads/audio/'; 
			$target_file_audio  =  $target_dir_audio . basename($filename_audio);
	        $array 		        =  explode('.', $filename_audio);
			$extension_audio    =  end($array);

			if ($request->get('filename') != $filename_audio ) {

        		return 'error';
        		
        	}
        	elseif ($extension_audio != 'mp3' && $extension_audio != 'mp4') {

        		return 'Sorry, only MP3, MP4 files are allowed.';

        	}
        	elseif (move_uploaded_file($upload_tmp_audio, $target_file_audio)) {

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
