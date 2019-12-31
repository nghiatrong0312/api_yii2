<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "organization".
 *
 * @property int|null $id
 * @property int|null $organizationLevelId
 * @property string|null $parentId
 * @property string|null $organizationCode
 * @property string|null $organizationName
 * @property int|null $isDeleted
 * @property string|null $createdAt
 * @property string|null $updatedAt
 * @property int|null $numberOfUsers
 * @property int|null $displayOrder
 */
class Organization extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organization';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'organizationLevelId', 'isDeleted', 'numberOfUsers', 'displayOrder'], 'integer'],
            [['parentId'], 'string', 'max' => 2],
            [['organizationCode'], 'string', 'max' => 6],
            [['organizationName'], 'string', 'max' => 16],
            [['createdAt', 'updatedAt'], 'string', 'max' => 26],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'organizationLevelId' => 'Organization Level ID',
            'parentId' => 'Parent ID',
            'organizationCode' => 'Organization Code',
            'organizationName' => 'Organization Name',
            'isDeleted' => 'Is Deleted',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'numberOfUsers' => 'Number Of Users',
            'displayOrder' => 'Display Order',
        ];
    }
}
