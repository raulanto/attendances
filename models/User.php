<?php

namespace app\models;

use webvimark\modules\UserManagement\models\queries\UserQuery;
use Yii;
use \yii\db\ActiveRecord;

class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    // ... tus propiedades existentes ...

    /**
    * @inheritdoc
    */
    public static function findIdentity($id)
    {
        return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
    * @inheritdoc
    */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
    * Finds user by username
    *
    * @param string $username
    * @return static|null
    */
    public static function findByUsername($username)
    {
        foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
    }

    /**
    * @inheritdoc
    */
    public function getId()
    {
        return $this->id;
    }

    /**
    * @inheritdoc
    */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
    * @inheritdoc
    */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
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

    // /**
    // * @inheritdoc
    // */
    // public static function find()
    // {
    //     return new UserQuery(get_called_class());
    // }
}
