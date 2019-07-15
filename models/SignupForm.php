<?php

namespace app\models;

use yii\base\Model;

class SignupForm extends Model
{
    const SCENARIO_REGISTER = 'register';

    public $username;
    public $password;
    public $passwordRepeat;

    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            [['username', 'password', 'passwordRepeat'], 'required'],
            ['username', 'unique', 'targetClass' => 'app\models\User', 'message' => 'Выбранный логин занят'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['password', 'string', 'min' => 6],
            ['passwordRepeat', 'compare', 'compareAttribute' => 'password']
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_REGISTER => ['username', 'password', 'passwordRepeat'],
        ];
    }

    /**
     * @return User|null
     * @throws \yii\base\Exception
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->setPassword($this->password);

            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Login',
            'password' => 'Password',
            'passwordRepeat' => 'Repeat'
        ];
    }
}