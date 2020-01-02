<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "audio".
 *
 * @property int $id
 * @property int $checkType
 * @property int $taskId
 * @property string $fileCode
 * @property string $fileName
 * @property string $createAt
 * @property string $updatedAt
 */
class Audio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'audio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['checkType', 'taskId', 'fileCode', 'fileName'], 'required'],
            [['checkType', 'taskId'], 'integer'],
            [['createAt', 'updatedAt'], 'safe'],
            [['fileCode', 'fileName'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'checkType' => 'Check Type',
            'taskId' => 'Task ID',
            'fileCode' => 'File Code',
            'fileName' => 'File Name',
            'createAt' => 'Create At',
            'updatedAt' => 'Updated At',
        ];
    }
}
