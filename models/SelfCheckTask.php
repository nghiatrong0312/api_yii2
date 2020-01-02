<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "self_check_task".
 *
 * @property int $id
 * @property string $userCode
 * @property int $workId
 * @property int $workTypeId
 * @property int $state
 * @property string $createdAt
 * @property string $updatedAt
 * @property int|null $isAutomatic
 */
class SelfCheckTask extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'self_check_task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['userCode', 'workId', 'workTypeId', 'state'], 'required'],
            [['workId', 'workTypeId', 'state', 'isAutomatic'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['userCode'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userCode' => 'User Code',
            'workId' => 'Work ID',
            'workTypeId' => 'Work Type ID',
            'state' => 'State',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'isAutomatic' => 'Is Automatic',
        ];
    }
}
