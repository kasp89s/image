<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * PasswordRecovery.
 */
class PasswordRecovery extends Model
{
	const SCENARIO_CODE = 'authCode';
	const SCENARIO_RECOVERY = 'recovery';

    public $username;
    public $password;
    public $password_retype;

	public $_user;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username'], 'required', 'on' => self::SCENARIO_CODE, 'message' => \Yii::t('app', 'Fields can not be empty.')],
			['username', 'string', 'min' => 6, 'max' => 30, 'message' => \Yii::t('app', 'The field must contain from 6 to 30 characters.')],
			[['password', 'password_retype'], 'required', 'on' => self::SCENARIO_RECOVERY, 'message' => \Yii::t('app', 'Field can not be empty.')],
			['password', 'string', 'min' => 6, 'max' => 16, 'message' => \Yii::t('app', 'The field must contain from 6 to 16 characters.')],
			['password_retype', 'compare', 'compareAttribute'=>'password', 'message' => \Yii::t('app', "Passwords don't match") ],
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function recover()
    {
        if ($this->validate()) {
            $user = User::find()->where(['username' => $this->username])->one();
			if (empty($user)) {
				$user = User::find()->where(['email' => $this->username])->one();
            }
			
			if (empty($user)) {
				$this->addError('username', \Yii::t('app', 'Username or email is not valid.'));
            } else {
				$this->_user = $user;
				return true;
			}
        }
        return false;
    }
}
