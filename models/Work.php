<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "work".
 *
 * @property int $id
 * @property string|null $userCode
 * @property int|null $workedOrganizationId
 * @property string|null $code
 * @property string|null $officeComment
 * @property int|null $status
 * @property string|null $start
 * @property string|null $end
 * @property int|null $checkType
 * @property string|null $date
 * @property string|null $powerAlImage
 * @property string|null $createdAt
 * @property string|null $updatedAt
 * @property int|null $confirmStatus
 * @property int|null $isObjectRecognizeSuccess
 * @property string|null $faOperationId
 * @property string|null $faOperationKind
 * @property string|null $faOperationDetail
 * @property string|null $faOperationDate
 * @property string|null $faConstructionTimePeriod
 */
class Work extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'work';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['workedOrganizationId', 'status', 'checkType', 'confirmStatus', 'isObjectRecognizeSuccess'], 'integer'],
            [['start', 'end', 'date', 'createdAt', 'updatedAt'], 'safe'],
            [['userCode', 'code'], 'string', 'max' => 45],
            [['officeComment'], 'string', 'max' => 300],
            [['powerAlImage'], 'string', 'max' => 255],
            [['faOperationId', 'faOperationKind', 'faOperationDate', 'faConstructionTimePeriod'], 'string', 'max' => 30],
            [['faOperationDetail'], 'string', 'max' => 100],
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
            'workedOrganizationId' => 'Worked Organization ID',
            'code' => 'Code',
            'officeComment' => 'Office Comment',
            'status' => 'Status',
            'start' => 'Start',
            'end' => 'End',
            'checkType' => 'Check Type',
            'date' => 'Date',
            'powerAlImage' => 'Power Al Image',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'confirmStatus' => 'Confirm Status',
            'isObjectRecognizeSuccess' => 'Is Object Recognize Success',
            'faOperationId' => 'Fa Operation ID',
            'faOperationKind' => 'Fa Operation Kind',
            'faOperationDetail' => 'Fa Operation Detail',
            'faOperationDate' => 'Fa Operation Date',
            'faConstructionTimePeriod' => 'Fa Construction Time Period',
        ];
    }
}
