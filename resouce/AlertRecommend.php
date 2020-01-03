<?php
namespace app\resouce;

use yii;


/**
 * 
 */
class AlertRecommend extends \app\models\AlertRecommend
{
	public function fields()
	{
		return ['id', 'flowType', 'workTypeId', 'organizationId', 'recommend'];
	}
}