<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "logs".
 *
 * @property int $id
 * @property string|null $flow
 * @property string|null $workDate
 * @property int|null $userId
 * @property int|null $workId
 * @property string|null $eventId
 * @property int $powerAlResult
 * @property string $powerAlInTime
 * @property string $powerAlOutTime
 * @property float $powerAlCertainty
 * @property int $workTypeId
 * @property int $status
 * @property string|null $time
 * @property string|null $datetime
 * @property string $ObjectRecognitionResult
 */
class Logs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['workDate', 'datetime'], 'safe'],
            [['userId', 'workId', 'powerAlResult', 'workTypeId', 'status'], 'integer'],
            [['powerAlResult', 'powerAlInTime', 'powerAlOutTime', 'powerAlCertainty', 'workTypeId', 'status', 'ObjectRecognitionResult'], 'required'],
            [['powerAlCertainty'], 'number'],
            [['flow', 'ObjectRecognitionResult'], 'string', 'max' => 255],
            [['eventId'], 'string', 'max' => 4],
            [['powerAlInTime', 'powerAlOutTime', 'time'], 'string', 'max' => 12],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'flow' => 'Flow',
            'workDate' => 'Work Date',
            'userId' => 'User ID',
            'workId' => 'Work ID',
            'eventId' => 'Event ID',
            'powerAlResult' => 'Power Al Result',
            'powerAlInTime' => 'Power Al In Time',
            'powerAlOutTime' => 'Power Al Out Time',
            'powerAlCertainty' => 'Power Al Certainty',
            'workTypeId' => 'Work Type ID',
            'status' => 'Status',
            'time' => 'Time',
            'datetime' => 'Datetime',
            'ObjectRecognitionResult' => 'Object Recognition Result',
        ];
    }
}
