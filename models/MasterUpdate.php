<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "master_update".
 *
 * @property int $id
 * @property string|null $key
 * @property string $updateTime
 */
class MasterUpdate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'master_update';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'integer'],
            [['updateTime'], 'safe'],
            [['key'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'updateTime' => 'Update Time',
        ];
    }
}
