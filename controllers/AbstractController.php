<?php

namespace app\controllers;

use app\components\Mobile_Detect;
use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\LoginForm;
use app\models\RegisterForm;

$phpMailer = Yii::getAlias('@app/vendor/phpmailer/PHPMailer.php');
require_once($phpMailer);

class AbstractController extends Controller {

    public $session = false;

    public $user = false;

    public $_theme = '';

    public function init()
    {
        // Управление версиями броузера.
        if (!empty($_GET['version'])) {
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'force_version',
                'value' => $_GET['version'],
            ]));
            $forceVersion = $_GET['version'];
        } else {
            $forceVersion = Yii::$app->request->cookies->getValue('force_version');
        }

        if (!$forceVersion) {
            $detect = new Mobile_Detect();
            if (($detect->isMobile() || $detect->isTablet()) && $_SERVER['HTTP_HOST'] == Yii::$app->params['version']['full']['domain']) {
                return $this->redirect(Yii::$app->params['version']['mobile']['host'], 302);
            }
        } elseif ($forceVersion == 'full' && $_SERVER['HTTP_HOST'] == Yii::$app->params['version']['mobile']['domain']) {
            return $this->redirect(Yii::$app->params['version']['full']['host'], 302);
        } elseif ($forceVersion == 'mobile' && $_SERVER['HTTP_HOST'] == Yii::$app->params['version']['full']['domain']) {
            return $this->redirect(Yii::$app->params['version']['mobile']['host'], 302);
        }

        if ($_SERVER['HTTP_HOST'] == 'm.qruto.com') {
            $this->layout = 'mobile';
            $this->_theme = 'mobile/';
        }
        $this->session = Yii::$app->session;
        if (!$this->session->isActive) {
            $this->session->open();
        }

        Yii::$app->language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

        if (!empty($_GET['l']) && isset(Yii::$app->params['languages'][$_GET['l']])) {
            $this->session->set('language', $_GET['l']);
        }

        if (!empty($this->session['language'])) {
            Yii::$app->language = $this->session->get('language');
        }
		if (!\Yii::$app->user->isGuest) {
			$this->user = User::find()->where(['id' => \Yii::$app->user->id])->one();
			$favorites = $this->session->get('favorites');
			if (!isset($favorites)) {
				$favorites = [];
				if (!empty($this->user->favorites)) {
					foreach ($this->user->favorites as $favorite){
						$favorites[] = $favorite['postId'];
					}
				}

				$this->session->set('favorites', $favorites);
			}

			$likes = $this->session->get('likePost');
			if (!isset($likes)) {
				$this->session->set('likePost', json_decode($this->user->likePost, true));
			}
		}
    }

    public function actionLogin()
    {
        if (\Yii::$app->user->isGuest) {
            $model = new LoginForm();
            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                return $this->goHome();
            }
            echo \yii\helpers\BaseJson::encode($model->getErrors());
        }

        Yii::$app->end();
    }

	public function actionCheck()
    {
		$model = new RegisterForm(['scenario' => RegisterForm::SCENARIO_CHECK]);
		
		if($model->load(Yii::$app->request->post()) && $model->validate()) {
			Yii::$app->end();
		}

		echo \yii\helpers\BaseJson::encode($model->getErrors());
	}
    public function actionRegister()
    {
        $model = new RegisterForm(['scenario' => RegisterForm::SCENARIO_REGISTER]);

        if($model->load(Yii::$app->request->post()) && $model->register()) {
				$mailer = new \PHPMailer();
				$mailer->setFrom(Yii::$app->params['adminEmail']);
				$mailer->addAddress($model->_user->email);
				$mailer->isHTML(true);

				$mailer->Subject = \yii\helpers\Url::base();
				$mailer->Body    = $this->renderPartial('emailTemplates/registrationMail', [
						'username' => $model->_user->username,
						'email' => $model->_user->email,
						'password' => $model->password,
					]);
				if(!$mailer->send()) {
					throw new Exception('Mailer Error: ' . $mailer->ErrorInfo);
				}
				
			echo \yii\helpers\BaseJson::encode(['success' => true]);
			Yii::$app->end();
        }

        echo \yii\helpers\BaseJson::encode($model->getErrors());
    }

    /**
     * Exit.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout(false);

        return $this->goHome();
    }
}
