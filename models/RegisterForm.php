<?php

namespace app\models;

use Yii;
use yii\base\Model;
/**
 * RegisterForm is the model behind the login form.
 */
class RegisterForm extends Model
{
	const SCENARIO_REGISTER = 'register';
	const SCENARIO_CHECK = 'check';
    /**
     * Email.
     *
     * @var
     */
    public $email;

    /**
     * Captcha.
     *
     * @var
     */
    public $captcha;

    /**
     * @var
     */
    public $username;
    public $password_retype;
    public $password;

    /**
     * Идентификатор юзера после регистрации.
     *
     * @var
     */
    public $id;

	public $_user;
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password', 'password_retype', 'captcha'], 'required', 'on' => self::SCENARIO_REGISTER, 'message' => \Yii::t('app', 'Field can not be empty.')],
            [['username', 'email', 'password', 'password_retype'], 'required', 'on' => self::SCENARIO_CHECK, 'message' => \Yii::t('app', 'Field can not be empty.')],
            //['username', 'email', 'message' => \Yii::t('app', 'The field must contain a valid email.')],
            ['username', 'string', 'min' => 6, 'max' => 10, 'message' => \Yii::t('app', 'The field must contain from 6 to 10 characters.')],
            ['password', 'string', 'min' => 6, 'max' => 16, 'message' => \Yii::t('app', 'The field must contain from 6 to 16 characters.')],
			['password_retype', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match" ],
			['email', 'email', 'message' => \Yii::t('app', 'The field must contain a valid email.')],
            ['captcha', 'captcha', 'on' => self::SCENARIO_REGISTER],
        ];
    }

	public function check()
	{
		if ($this->validate()) {
			$user = User::find()->where(['username' => $this->username])->one();
			if (!empty($user)) {
                $this->addError('email', \Yii::t('app', 'Such a user already exists.'));
                return false;
            }
			$user = User::find()->where(['email' => $this->email])->one();
			if (!empty($user)) {
                $this->addError('email', \Yii::t('app', 'Such a user already exists.'));
                return false;
            }
		}
	}
	
    public function register()
    {
        if ($this->validate()) {
            $user = new User();
			$user->username = $this->username;
			$user->email = $this->email;
            $user->password = md5($this->password);
            $user->save();
			$this->_user = $user;
            $this->id = $user->id;
            Yii::$app->user->login($user, true ? 3600*24*30 : 0);
            return true;
        }
        return false;
    }
}
