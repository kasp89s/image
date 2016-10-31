<?php

namespace app\models;

use Yii;
use yii\base\Model;
/**
 * RegisterForm is the model behind the login form.
 */
class SettingForm extends Model
{
    /**
     * Email.
     *
     * @var
     */
    public $email;

    /**
     * @var
     */
    public $username;
    public $newPassword_retype;
    public $newPassword;
    public $password;

    public $mature;
    public $notification;
    public $promotional;
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['mature', 'notification', 'promotional'], 'safe'],
            [['username', 'email', 'password'], 'required', 'message' => \Yii::t('app', 'Field can not be empty.')],
            ['username', 'string', 'min' => 6, 'max' => 10, 'message' => \Yii::t('app', 'The field must contain from 6 to 10 characters.')],
            ['password', 'string', 'min' => 6, 'max' => 16, 'message' => \Yii::t('app', 'The field must contain from 6 to 16 characters.')],
            ['newPassword', 'string', 'min' => 6, 'max' => 16, 'message' => \Yii::t('app', 'The field must contain from 6 to 16 characters.')],
			['newPassword_retype', 'compare', 'compareAttribute'=>'newPassword', 'message'=>"Passwords don't match" ],
			['email', 'email', 'message' => \Yii::t('app', 'The field must contain a valid email.')],
        ];
    }

	public function check()
	{
		if ($this->validate()) {
			$user = User::find()->where(['id' => \Yii::$app->user->id])->one();
			if ($user->password != md5($this->password)) {
                $this->addError('password', \Yii::t('app', 'Incorrect password.'));
                return false;
            }
			if (!empty($this->newPassword) && ($this->newPassword != $this->newPassword_retype)) {
				$this->addError('newPassword_retype', \Yii::t('app', 'Passwords don\'t match.'));
				return false;
			}
			$user = User::find()
			->where(['username' => $this->username])
			->andWhere('id != :id',[':id' => \Yii::$app->user->id])
			->one();
			if (!empty($user)) {
                $this->addError('username', \Yii::t('app', 'Such a user already exists.'));
                return false;
            }
			$user = User::find()
			->where(['email' => $this->email])
			->andWhere('id != :id',[':id' => \Yii::$app->user->id])
			->one();
			if (!empty($user)) {
                $this->addError('email', \Yii::t('app', 'Such a user already exists.'));
                return false;
            }
			
			return true;
		}
	    return false;
	}
	
	public function saveUser()
	{
		$user = User::find()->where(['id' => \Yii::$app->user->id])->one();
		
		$user->password = !empty($this->newPassword) ? md5($this->newPassword) : $user->password;
		$user->username = $this->username;
		$user->email = $this->email;
		$user->mature = $this->mature;
		$user->notification = !$this->notification;
		$user->promotional = !$this->promotional;
		$user->save();
	}
}
