<?php

namespace app\services\user;

use app\models\User;
use yii\base\BaseObject;

class AuthService extends BaseObject {

    private function login(string $nickname) {
        if($user = $this->getUser($nickname)) {
            \Yii::$app->user->login($user,3600 * 24);
        }
    }

    private function register(string $nickname): User {
        $user = new User([
            'login' => $nickname
        ]);
        $user->save();

        return $user;
    }

    private function getUser(string $nickname) :? User{
        return User::findOne(['login' => $nickname]);
    }

    public function loginWithRegistrationIfNeeded(string $login) {
        if(!$this->getUser($login)) {
            $this->register($login);
        }

        $this->login($login);
    }

}
