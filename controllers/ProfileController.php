<?php

namespace app\controllers;

use app\models\UserComment;
use Faker\Provider\UserAgent;
use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\UserAlbum;
use app\models\UserImage;
use app\models\SettingForm;
use \BW\Vkontakte as Vk;

$oAuth = Yii::getAlias('@app/vendor/yahoo5/OAuth/OAuth.php');
$yahoo = Yii::getAlias('@app/vendor/yahoo5/Yahoo/YahooOAuthApplication.class.php');

require_once($oAuth);
require_once($yahoo);
class ProfileController extends AbstractController
{
    public $layout = 'main';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
                ],
            ],
        ];
    }

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
     * ���������.
     *
     * @return string
     */
    public function actionSettings()
    {
        if (\Yii::$app->user->isGuest) {
            throw new \yii\web\NotFoundHttpException();
        }
        \Yii::$app->params['seo']['title'] = \Yii::t('app', 'Settings for {username} - QRUTO', ['username' => $this->user->username]);
        $model = new SettingForm();
        if ($model->load(Yii::$app->request->post()) && $model->check()) {
            $model->saveUser();
            $this->user = User::find()->where(['id' => \Yii::$app->user->id])->one();
            Yii::$app->view->params['user'] = $this->user;
        }
        return $this->render($this->_theme . 'settings', ['model' => $model, 'errors' => $model->getErrors()]);
    }

    public function actionAlbum()
    {
        \Yii::$app->params['seo']['title'] = \Yii::t('app', '{username} ~ albums - QRUTO', ['username' => $this->user->username]);
        return $this->render($this->_theme . 'album', []);
    }

    public function actionImageHandler($id)
    {
        $image = UserImage::findOne($id);
        if (empty($image)) {
            throw new \yii\web\NotFoundHttpException();
        }

        $model = UserImage::find()
            ->joinWith('albumImages', false)
            ->select('album_image.albumId, user_image.*')
            ->andWhere('user_image.userId = :userId', [':userId' => $this->user->id])
            ->andWhere('user_image.id != :id', [':id' => $id])
            ->andWhere('album_image.albumId IS NULL');
        $startImages = $model->all();

        return $this->render($this->_theme . 'image-handler', [
            'image' => $image,
            'startImages' => $startImages,
        ]);
    }

    public function actionCreateAlbum()
    {
        if (Yii::$app->request->isPost) {
            $model = new UserAlbum();
            $model->userId = $this->user->id;
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->save();
            }
            return $this->redirect('/profile/album',302);
        }

        return $this->render($this->_theme . 'create-album');
    }

    public function actionEditAlbum($id)
    {
        $album = UserAlbum::findOne($id);
        if (empty($album)) {
            throw new \yii\web\NotFoundHttpException();
        }

        if (Yii::$app->request->isPost) {
            if ($album->load(Yii::$app->request->post()) && $album->validate()) {
                $album->save();
            }
        }
        return $this->render($this->_theme . 'edit-album', ['album' => $album]);
    }

    public function actionChangeAlbum($id)
    {
        \Yii::$app->params['seo']['title'] = \Yii::t('app', 'Edit images titles or descriptions - QRUTO');
        $album = UserAlbum::findOne($id);

        if (Yii::$app->request->isPost) {
            unset($_POST['_csrf']);
            foreach ($_POST as $post) {
                $model = UserImage::find()->where(['id' => $post['UserImage']['id']])->one();
                if (empty($model))
                    throw new \yii\web\NotFoundHttpException();

                if ($model->load($post) && $model->validate()) {
                    $model->save();
                }
            }
            return $this->redirect('/profile/album',302);
        }

        return $this->render('changeAlbum', ['images' => $album->images]);
    }

    public function actionRearrangeAlbumImages($id)
    {
        \Yii::$app->params['seo']['title'] = \Yii::t('app', 'Rearrange images - QRUTO');
        $album = UserAlbum::findOne($id);

        return $this->render('rearrangeAlbumImages', ['images' => $album->images]);
    }

    public function actionRemoveAlbum($id)
    {
        $album = UserAlbum::findOne($id);

        return $this->render('removeAlbum', ['album' => $album]);
    }

    public function actionImage()
    {
        if (\Yii::$app->user->isGuest) {
            throw new \yii\web\NotFoundHttpException();
        }
        \Yii::$app->params['seo']['title'] = \Yii::t('app', '{username} ~ images - QRUTO', ['username' => $this->user->username]);
        if (Yii::$app->request->isPost) {
            // Если были выбраны картинки в не альбома.
            if ($_POST['albumId'] == 0) {
                $selectedOption = \Yii::t('app', 'Non album images');
                $selectedId = 0;

                $model = UserImage::find()
                    ->joinWith('albumImages', false)
                    ->select('album_image.albumId, user_image.*')
                    ->andWhere('user_image.userId = :userId', [':userId' => $this->user->id])
                    ->andWhere('album_image.albumId IS NULL');

                if ($_POST['order_value'] == 'name') {
                    if ($_POST['order_type'] == 'asc') {
                        $model->orderBy(['user_image.name' => 'asc']);
                    } elseif ($_POST['order_type'] == 'desc') {
                        $model->orderBy(['user_image.name' => 'desc']);
                    }
                } elseif($_POST['order_value'] == 'time') {
                    if ($_POST['order_type'] == 'asc') {
                        $model->orderBy(['user_image.date' => 'asc']);
                    } elseif ($_POST['order_type'] == 'desc') {
                        $model->orderBy(['user_image.date' => 'desc']);
                    }
                }

                $startImages = $model->all();
            } else {
                // Был выбран альбом
                $album = UserAlbum::findOne($_POST['albumId']);

                $startImages = $album->imagesTimeAsc;
                if ($_POST['order_value'] == 'name') {
                    if ($_POST['order_type'] == 'asc') {
                        $startImages = $album->imagesNameAsc;
                    } elseif ($_POST['order_type'] == 'desc') {
                        $startImages = $album->imagesNameDesc;
                    }
                } elseif($_POST['order_value'] == 'time') {
                    if ($_POST['order_type'] == 'asc') {
                        $startImages = $album->imagesTimeAsc;
                    } elseif ($_POST['order_type'] == 'desc') {
                        $startImages = $album->imagesTimeDesc;
                    }
                }

                $selectedOption = $album->title;
                $selectedId = $album->id;

            }

        } else {

            // Ищим картинки не в альбомах.
            $selectedOption = \Yii::t('app', 'Non album images');
            $selectedId = 0;
            $startImages = UserImage::find()
                ->joinWith('albumImages', false)
                ->select('album_image.albumId, user_image.*')
                ->andWhere('user_image.userId = :userId', [':userId' => $this->user->id])
                ->andWhere('album_image.albumId IS NULL')
                ->all();
            if (empty($startImages) || count($startImages) == 0) {
                // Если картинки не нашлись берем альбом и показуем с него картинки.
                $album = UserAlbum::find()->where(['userId' => $this->user->id])->one();
                if (!empty($album)) {
                    $startImages = $album->imagesTimeAsc;
                    $selectedOption = $album->title;
                    $selectedId = $album->id;
                }
            }
        }


        $countImages = UserImage::find()->where(['userId' => $this->user->id])->count();
        return $this->render(
            $this->_theme . 'photo',
            [
                'startImages' => $startImages,
                'selectedOption' => $selectedOption,
                'selectedId' => $selectedId,
                'countImages' => $countImages,
            ]
        );
    }

    public function actionGallery($username)
    {
        \Yii::$app->params['seo']['title'] = \Yii::t('app', '{username} ~ images - QRUTO', ['username' => $username]);
        if (!\Yii::$app->user->isGuest && $this->user->username == $username) {
            return $this->selfGallery();
        }

        return $this->guestGallery($username);
    }

    public function actionComments($username)
    {
        if (\Yii::$app->user->isGuest || $this->user->username != $username) {
            $user = User::find()->where(['username' => $username])->one();
            if (empty($user)) {
                throw new \yii\web\NotFoundHttpException();
            }
        } else {
            $user = $this->user;
        }

        return $this->render('comments', ['user' => $user]);
    }

    public function actionFavorites($username)
    {
        if (\Yii::$app->user->isGuest || $this->user->username != $username) {
            $user = User::find()->where(['username' => $username])->one();
            if (empty($user)) {
                throw new \yii\web\NotFoundHttpException();
            }
        } else {
            $user = $this->user;
        }

        return $this->render('favorites', ['user' => $user]);
    }

    public function actionReplies($username)
    {
        if (\Yii::$app->user->isGuest || $this->user->username != $username) {
            throw new \yii\web\NotFoundHttpException();
        }
        $user = $this->user;
        $comments = UserComment::find()
            ->joinWith('replies')
            ->andWhere('user_comment.userId = :userId', [':userId' => $this->user->id])
            ->andWhere('comment_reply.readOwner = :readOwner', [':readOwner' => 0])
            ->orderBy(['user_comment.date' => SORT_DESC])
            ->all();
        return $this->render('replies', ['user' => $user, 'comments' => $comments]);
    }

    private function selfGallery()
    {
        return $this->render($this->_theme . 'self', ['user' => $this->user]);
    }

    private function guestGallery($username)
    {
        $user = User::find()->where(['username' => $username])->one();
        if (empty($user)) {
            throw new \yii\web\NotFoundHttpException();
        }
//        var_dump(substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)); exit;
        return $this->render($this->_theme . 'self', ['user' => $user]);
    }
}
