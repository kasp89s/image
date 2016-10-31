<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;

class AbstractController extends Controller {

    public $user = false;

    public function init()
    {
        if (!\Yii::$app->user->isGuest) {
            $this->user = User::findOne(\Yii::$app->user->id);
        } else {
            Yii::$app->response->redirect(array('site/index'));
        }
    }

    /**
     * Exit.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}