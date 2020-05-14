<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $login Логин пользователя
 * @property string|null $auth_key Ключ аутентификации
 * @property string|null $access_token Токен доступа
 * @property float|null $balance Баланс пользователя
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
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
            [['login'], 'required'],
            [['balance'], 'number'],
            [['login'], 'string', 'max' => 16],
            [['auth_key', 'access_token'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Логин пользователя',
            'auth_key' => 'Ключ аутентификации',
            'access_token' => 'Токен доступа',
            'balance' => 'Баланс пользователя',
        ];
    }

    public static function findIdentity($id)
    {
        return User::findOne($id);
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function getId()
    {
        return $this->id;
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::findOne(['access_token' => $token]);
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }
}
