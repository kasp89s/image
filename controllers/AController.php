<?php

namespace app\controllers;

use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\UserAlbum;
use app\models\UserImage;
use app\models\AlbumImage;
use app\models\LoginForm;
use app\models\RegisterForm;
use app\models\PasswordRecovery;
use \BW\Vkontakte as Vk;

$oAuth = Yii::getAlias('@app/vendor/yahoo5/OAuth/OAuth.php');
$yahoo = Yii::getAlias('@app/vendor/yahoo5/Yahoo/YahooOAuthApplication.class.php');

require_once($oAuth);
require_once($yahoo);
class AController extends AbstractController
{
    public $layout = 'main';

	public function init()
	{
        parent::init();
        $facebook = new \Facebook\Facebook([
            'app_id' => Yii::$app->params['social']['facebook']['id'],
            'app_secret' => Yii::$app->params['social']['facebook']['secret'],
        ]);;
        $yahoo = new \YahooOAuthApplication(
            Yii::$app->params['social']['yahoo']['consumerKey'],
            Yii::$app->params['social']['yahoo']['consumerSecret'],
            Yii::$app->params['social']['yahoo']['applicationId'],
            'http://' . Yii::$app->getRequest()->serverName . '/social/yahoo');

        $vk = new Vk([
            'client_id' => Yii::$app->params['social']['vk']['id'],
            'client_secret' => Yii::$app->params['social']['vk']['secret'],
            'redirect_uri' => 'http://' . Yii::$app->getRequest()->serverName . '/social/vk',
        ]);

        $google = new \Google_Client();
        $google->setApplicationName('Qruto');
        $google->setScopes(array('https://www.googleapis.com/auth/plus.me'));
        $google->setClientId(Yii::$app->params['social']['google']['id']);
        $google->setClientSecret(Yii::$app->params['social']['google']['apiKey']);
        $google->setRedirectUri('http://' . Yii::$app->getRequest()->serverName . '/social/google');

        $weibo = new \SaeTOAuthV2(Yii::$app->params['social']['weibo']['ClientID'] , Yii::$app->params['social']['weibo']['ClientSecret']);

        Yii::$app->view->params['weibo'] = $weibo;
        Yii::$app->view->params['google'] = $google;
        Yii::$app->view->params['vk'] = $vk;
        Yii::$app->view->params['yahoo'] = $yahoo;
        Yii::$app->view->params['facebook'] = $facebook;
        Yii::$app->view->params['user'] = $this->user;
	}
    /**
     * Публикация альбома.
     */
    public function actionIndex($url)
    {
        if (!empty($this->session['initial'])) {
            return $this->renderPhotoAlbum($url);
        } else {
			$album = UserAlbum::find()->where(['url' => $url])->one();
			if (empty($album))
				throw new \yii\web\NotFoundHttpException();

            \Yii::$app->params['seo']['title'] = \Yii::t('app', '{title} - Album on QRUTO', [
                'title' => $album->title,
            ]);
            \Yii::$app->params['seo']['keywords'] = \Yii::t('app', 'pictures, images, funny, image hosting, image sharing, awesome images, current events, cute, cool pictures');
            \Yii::$app->params['seo']['description'] = \Yii::t('app', 'Album uploaded by {username}. {title}.', [
                'title' => $album->title,
                'username' => $album->user->username,
            ]);
			return $this->render($this->_theme . 'album', ['album' => $album]);
		}
    }

    private function renderPhotoAlbum($url)
    {
        $album = UserAlbum::find()->where(['url' => $url])->one();
        if (empty($album))
            throw new \yii\web\NotFoundHttpException();

        if (empty($album->cover)) {
            $album->cover = $album->images[0]->thumb90;
            $album->save();
        }
        $initial = $this->session->get('initial');

        \Yii::$app->params['seo']['title'] = \Yii::t('app', '{title} - Album on QRUTO', [
            'title' => $album->title,
        ]);
        \Yii::$app->params['seo']['keywords'] = \Yii::t('app', 'pictures, images, funny, image hosting, image sharing, awesome images, current events, cute, cool pictures');
        \Yii::$app->params['seo']['description'] = \Yii::t('app', 'Album uploaded by {username}. {title}.', [
            'title' => $album->title,
            'username' => $album->user->username,
        ]);
        return $this->render('//site/upload/' . $this->_theme . 'uploadAlbum', ['initial' => $initial, 'album' => $album]);
    }

    public function actionImage($url)
    {
        $image = UserImage::find()->where(['url' => $url])->one();
        if (empty($image))
            throw new \yii\web\NotFoundHttpException();

        \Yii::$app->params['seo']['title'] = \Yii::t('app', '{title} - QRUTO', [
            'title' => $image->title,
        ]);
        \Yii::$app->params['seo']['keywords'] = \Yii::t('app', 'pictures, images, funny, image hosting, image sharing, awesome images, current events, cute, cool pictures');
        \Yii::$app->params['seo']['description'] = \Yii::t('app', '{name} with {topic} uploaded by {username}. {title}', [
            'name' => $image->name,
            'topic' => !empty($image->post->topic) ? $image->post->topic : \Yii::t('app', 'No Topic'),
            'username' => $image->user->username,
            'title' => $image->title,
        ]);
        return $this->render('//site/upload/' . $this->_theme . 'uploadSingle', ['image' => $image]);
    }
}
