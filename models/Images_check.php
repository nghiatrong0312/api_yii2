<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "product".
 *

 */
class Images_check extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public function rules()
    {
        return [

            [['images'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 4],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [

            'images' => 'Images',

        ];
    }
}
