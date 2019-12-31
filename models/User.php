<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property int|null $organizationId
 * @property string|null $workChargeId
 * @property int|null $roleId
 * @property string|null $userCode
 * @property string|null $email
 * @property string|null $password
 * @property string|null $phone
 * @property string|null $status
 * @property int|null $attemptCount
 * @property string|null $isLocked
 * @property string|null $token
 * @property string|null $tokenCreateAt
 * @property int|null $isDeleted
 * @property string|null $createdAt
 * @property string|null $updatedAt
 * @property string|null $userName
 * @property int|null $kyotenType
 * @property string|null $companyCode
 * @property string|null $imei
 * @property string|null $supportStartAt
 * @property string|null $supportEndAt
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'organizationId', 'roleId', 'attemptCount', 'isDeleted', 'kyotenType'], 'integer'],
            [['workChargeId'], 'string', 'max' => 2],
            [['userCode'], 'string', 'max' => 9],
            [['email'], 'string', 'max' => 21],
            [['password'], 'string', 'max' => 60],
            [['phone'], 'string', 'max' => 11],
            [['status', 'isLocked', 'supportStartAt', 'supportEndAt'], 'string', 'max' => 10],
            [['token'], 'string', 'max' => 43],
            [['tokenCreateAt', 'createdAt', 'updatedAt'], 'string', 'max' => 26],
            [['userName', 'imei'], 'string', 'max' => 15],
            [['companyCode'], 'string', 'max' => 7],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'organizationId' => 'Organization ID',
            'workChargeId' => 'Work Charge ID',
            'roleId' => 'Role ID',
            'userCode' => 'User Code',
            'email' => 'Email',
            'password' => 'Password',
            'phone' => 'Phone',
            'status' => 'Status',
            'attemptCount' => 'Attempt Count',
            'isLocked' => 'Is Locked',
            'token' => 'Token',
            'tokenCreateAt' => 'Token Create At',
            'isDeleted' => 'Is Deleted',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'userName' => 'User Name',
            'kyotenType' => 'Kyoten Type',
            'companyCode' => 'Company Code',
            'imei' => 'Imei',
            'supportStartAt' => 'Support Start At',
            'supportEndAt' => 'Support End At',
        ];
    }
     public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($usernmane)
    {
        return static::findOne(['userCode' => $usernmane]);

    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
    
    public function getWork()
    {
        return $this->hasMany(Work::className(), ['userCode' => 'userCode']);
    }


}
