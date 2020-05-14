<?php

namespace app\services\user;

use app\models\User;
use yii\base\BaseObject;

/**
 * Class AuthService
 * @package app\services\user
 */
class AuthService extends BaseObject {

    /**
     * @param string $login
     */
    public function loginWithRegistrationIfNeeded(string $login) {
        if(!$this->getUser($login)) {
            $this->register($login);
        }

        $this->login($login);
    }

    /**
     * @param string $nickname
     */
    private function login(string $nickname) {
        if($user = $this->getUser($nickname)) {
            \Yii::$app->user->login($user,3600 * 24);
        }
    }

    /**
     * @param string $nickname
     * @return User
     */
    private function register(string $nickname): User {
        $user = new User([
            'login' => $nickname
        ]);
        $user->save();

        return $user;
    }

    /**
     * @param string $nickname
     * @return User|null
     */
    private function getUser(string $nickname) :? User{
        return User::findOne(['login' => $nickname]);
    }
}
