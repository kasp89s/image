<?php

namespace app\controllers;

use app\models\Tags;
use Yii;
use Facebook;
use \BW\Vkontakte as Vk;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\UserAlbum;
use app\models\UserImage;
use app\models\UserPost;
use app\models\PostImage;
use app\models\AlbumImage;
use app\models\LoginForm;
use app\models\RegisterForm;
use app\models\PasswordRecovery;

$oAuth = Yii::getAlias('@app/vendor/yahoo5/OAuth/OAuth.php');
$yahoo = Yii::getAlias('@app/vendor/yahoo5/Yahoo/YahooOAuthApplication.class.php');

require_once($oAuth);
require_once($yahoo);
class SiteController extends AbstractController
{
    public $layout = 'main';

    public $facebook;

    public $yahoo;

    public $vk;

    public $google;

    public $weibo;

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

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'width' => 80,
                'height' => 42,
                'minLength' => 4,
                'maxLength' => 4
            ],
        ];
    }

    public function init()
    {
        parent::init();

        $this->facebook = new \Facebook\Facebook([
            'app_id' => Yii::$app->params['social']['facebook']['id'],
            'app_secret' => Yii::$app->params['social']['facebook']['secret'],
        ]);;

        $this->yahoo = new \YahooOAuthApplication(
            Yii::$app->params['social']['yahoo']['consumerKey'],
            Yii::$app->params['social']['yahoo']['consumerSecret'],
            Yii::$app->params['social']['yahoo']['applicationId'],
            'http://' . Yii::$app->getRequest()->serverName . '/social/yahoo');

        $this->vk = new Vk([
            'client_id' => Yii::$app->params['social']['vk']['id'],
            'client_secret' => Yii::$app->params['social']['vk']['secret'],
            'redirect_uri' => 'http://' . Yii::$app->getRequest()->serverName . '/social/vk',
        ]);

        $this->google = new \Google_Client();
        $this->google->setApplicationName('Qruto');
        $this->google->setScopes(array('https://www.googleapis.com/auth/plus.me'));
        $this->google->setClientId(Yii::$app->params['social']['google']['id']);
        $this->google->setClientSecret(Yii::$app->params['social']['google']['apiKey']);
        $this->google->setRedirectUri('http://' . Yii::$app->getRequest()->serverName . '/social/google');

        $this->weibo = new \SaeTOAuthV2(Yii::$app->params['social']['weibo']['ClientID'] , Yii::$app->params['social']['weibo']['ClientSecret']);

        Yii::$app->view->params['weibo'] = $this->weibo;
        Yii::$app->view->params['google'] = $this->google;
        Yii::$app->view->params['vk'] = $this->vk;
        Yii::$app->view->params['yahoo'] = $this->yahoo;
        Yii::$app->view->params['facebook'] = $this->facebook;
        Yii::$app->view->params['user'] = $this->user;
    }

    /**
     * Главная.
     *
     * @return string
     */
    public function actionIndex()
    {
        //$this->session->remove('initial');
        //$this->session->remove('images');
        $cookies = Yii::$app->request->cookies;

        $query = UserPost::find()
            ->where(['language' => Yii::$app->language])
            ->andWhere(['active' => 1]);
        if (\Yii::$app->user->isGuest || $this->user->mature == 0) {
            $query->andWhere(['mature' => 0]);
        }

        $sort_topic = $cookies->getValue('sort_topic', 'all');
        if (
            isset($_GET['sort_topic']) &&
            !empty(\Yii::$app->params['topicValues'][$_GET['sort_topic']]) &&
            $_GET['sort_topic'] != 'all'
        ) {
            $sort_topic = $_GET['sort_topic'];
//            $query->andWhere(['topic' => $_GET['sort_topic']]);
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'sort_topic',
                'value' => $_GET['sort_topic'],
                'expire' => time() + 60 * 60 * 24 * 30,
            ]));
        } else {
            $sort_topic = 'all';
        }

        switch ($sort_topic) {
            case 'all': $topic = \Yii::t('app', 'All images');
                break;
            default:
                $topic = \Yii::$app->params['topicValues'][$sort_topic]['title'];
                $query->andWhere(['topic' => $sort_topic]);
                break;
        }

        $sort = $cookies->getValue('sort', 'date');
        if (!empty($_GET['sort']) && $_GET['sort'] == 'date') {
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'sort',
                'value' => 'date',
                'expire' => time() + 60 * 60 * 24 * 30,
            ]));
            $sort = 'date';
        } elseif(!empty($_GET['sort']) && $_GET['sort'] == 'rand') {
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'sort',
                'value' => 'rand',
                'expire' => time() + 60 * 60 * 24 * 30,
            ]));
            $sort = 'rand';
        } elseif(!empty($_GET['sort']) && $_GET['sort'] == 'view') {
            Yii::$app->response->cookies->add(new \yii\web\Cookie([
                'name' => 'sort',
                'value' => 'view',
                'expire' => time() + 60 * 60 * 24 * 30,
            ]));
            $sort = 'view';
        }
        
        switch ($sort) {
            case 'date':
                $query->orderBy('id desc');
                break;
            case 'rand':
                $query->orderBy('RAND()');
                break;
            case 'view':
                $query->orderBy('viewCount desc');
                break;
            default:
                $query->orderBy('id desc');
                break;
        }

        $countQuery = clone $query;
        $pages = new Pagination([
                'totalCount' => $countQuery->count(),
                'pageSize' => Yii::$app->params['imagePerPageMain'],
                'defaultPageSize' => Yii::$app->params['imagePerPageMain']
            ]
        );
        $posts = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        return $this->render($this->_theme . 'index', [
            'posts' => $posts,
            'pages' => $pages,
            'topic' => $topic,
            'sort' => $sort,
            'sort_topic' => $sort_topic,
        ]);
    }

    public function actionFavorites()
    {
        \Yii::$app->params['seo']['title'] = \Yii::t('app', 'QRUTO: Your favorite images');

        return $this->render($this->_theme . 'favorites');
    }


    public function actionConstruction()
    {
        \Yii::$app->params['seo']['title'] = \Yii::t('app', 'Video to GIF - QRUTO');
        return $this->render('construction');
    }

    public function actionSearch()
    {
        if (empty($_GET['request']) && empty($_GET['tag']))
            throw new \yii\web\NotFoundHttpException();

        if (!empty($_GET['tag'])) {
            $tag = Tags::find()
                ->where(['title' => $_GET['tag']])
                ->one();

            return $this->render('searchTag', ['tag' => $tag]);
        }


        $query = UserPost::find()
            ->where(['language' => Yii::$app->language])
            ->andWhere(['active' => 1]);

        if (!empty($_GET['sort']) && $_GET['sort'] == 'date') {
            $query->orderBy('date desc');
        } elseif (!empty($_GET['sort']) && $_GET['sort'] == 'score') {
            $query->orderBy('likeCount desc');
        } elseif (!empty($_GET['sort']) && $_GET['sort'] == 'relevant') {
            $query->orderBy('viewCount desc');
        } else {
            $query->orderBy('date desc');
        }

        if (!empty($_GET['time']) && $_GET['time'] == 'today') {
            $query->andWhere('date > :date', [':date' => date('Y-m-d 00:00:00', time())]);
        } elseif (!empty($_GET['time']) && $_GET['time'] == 'week') {
            $query->andWhere('date > :date', [':date' => date('Y-m-d 00:00:00', strtotime("-7 day"))]);
        } elseif (empty($_GET['time']) || $_GET['time'] == 'month') {
            $query->andWhere('date > :date', [':date' => date('Y-m-d 00:00:00', strtotime("-1 month"))]);
        } elseif (!empty($_GET['time']) && $_GET['time'] == 'year') {
            $query->andWhere('date > :date', [':date' => date('Y-m-d 00:00:00', strtotime("-1 year"))]);
        } elseif (!empty($_GET['time']) && $_GET['time'] == 'all') {

        }

        if (\Yii::$app->user->isGuest || $this->user->mature == 0) {
           $query->andWhere(['mature' => 0]);
        }

        $query->andFilterWhere(['like', 'title', $_GET['request']]);

        $posts = $query->all();

        return $this->render('search', ['posts' => $posts]);
    }

    public function actionLoadImage()
    {
        $query = UserPost::find()
            ->where(['language' => Yii::$app->language])
            ->andWhere(['active' => 1]);
        if (\Yii::$app->user->isGuest || $this->user->mature == 0) {
            $query->andWhere(['mature' => 0]);
        }

        if (
            isset($_GET['sort_topic']) &&
            !empty(\Yii::$app->params['topicValues'][$_GET['sort_topic']]) &&
            $_GET['sort_topic'] != 'all'
        ) {
            $query->andWhere(['topic' => $_GET['sort_topic']]);
        }

        if (!empty($_GET['sort']) && $_GET['sort'] == 'date') {
            $query->orderBy('id desc');
        } elseif(!empty($_GET['sort']) && $_GET['sort'] == 'rand') {
            $query->orderBy('RAND()');
        } else {
            $query->orderBy('viewCount desc');
        }

        $countQuery = clone $query;
        $pages = new Pagination([
                'totalCount' => $countQuery->count(),
                'pageSize' => Yii::$app->params['imagePerPageMain'],
                'defaultPageSize' => Yii::$app->params['imagePerPageMain']
            ]
        );
        $posts = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        echo $this->renderPartial($this->_theme . 'ajax-image-load', ['posts' => $posts]);
        Yii::$app->end();
    }

    public function actionImage($url)
    {
        $post = UserPost::find()->where(['url' => $url])->one();
        if (empty($post) && !empty($this->session['initial'])) {
            return $this->renderPhotoSingle($url);
        } elseif (empty($post)) {
            throw new \yii\web\NotFoundHttpException();
        }


        $post->viewCount += 1;
        $post->save();
        $album = isset($post->images[0]->album) ? $post->images[0]->album : false;

        $cookies = \Yii::$app->request->cookies;
        $sort_topic = $cookies->getValue('sort_topic', 'all');
        $sort = $cookies->getValue('sort', 'date');

        $query = UserPost::find()
            ->where(['language' => Yii::$app->language])
            ->andWhere(['active' => 1]);

        $prevPostQuery = UserPost::find()
            ->where(['language' => Yii::$app->language])
            ->andWhere(['active' => 1]);

        if (\Yii::$app->user->isGuest || $this->user->mature == 0) {
            $query->andWhere(['mature' => 0]);
            $prevPostQuery->andWhere(['mature' => 0]);
        }

        switch ($sort_topic) {
            case 'all': $topic = \Yii::t('app', 'All images');
                break;
            default:
                $topic = \Yii::$app->params['topicValues'][$sort_topic]['title'];
                $query->andWhere(['topic' => $sort_topic]);
                $prevPostQuery->andWhere(['topic' => $sort_topic]);
                break;
        }

        switch ($sort) {
            case 'date':
                $query->andWhere("id < '{$post->id}'");
                $query->andWhere("id != '{$post->id}'");
                $prevPostQuery->andWhere("id != '{$post->id}'");
                $prevPostQuery->andWhere("id > '{$post->id}'");
                $query->orderBy('id desc');
                $prevPostQuery->orderBy('id asc');
                break;
            case 'rand':
                $query->orderBy('RAND()');
                $prevPostQuery->orderBy('RAND()');
                break;
            case 'view':
                $query->andWhere("viewCount <= '{$post->viewCount}'");
                $query->andWhere("id != '{$post->id}'");
                $prevPostQuery->andWhere("id != '{$post->id}'");
                $prevPostQuery->andWhere("viewCount >= '{$post->viewCount}'");
                $query->orderBy('viewCount desc, id desc');
                $prevPostQuery->orderBy('viewCount asc, id asc');
                break;
            default:
                $query->andWhere("id < '{$post->date}'");
                $query->andWhere("id != '{$post->id}'");
                $prevPostQuery->andWhere("id != '{$post->id}'");
                $prevPostQuery->andWhere("id > '{$post->id}'");
                $query->orderBy('id desc');
                $prevPostQuery->orderBy('id asc');
                break;
        }

        $rightBarPosts = $query->limit(\Yii::$app->params['imageRightBar'])->all();
        $prevPost = $prevPostQuery->one();

        \Yii::$app->params['seo']['title'] = \Yii::t('app', '{title} - Album on QRUTO', [
            'title' => $post->title,
        ]);

        \Yii::$app->params['seo']['keywords'] = \Yii::t('app', 'pictures, images, funny, image hosting, image sharing, awesome images, current events, cute, cool pictures');
        \Yii::$app->params['seo']['description'] = \Yii::t('app', 'Album with topic of {topic} uploaded by {username}. {title}.', [
            'topic' => \Yii::$app->params['topicValues'][$post->topic]['title'],
            'title' => $post->title,
            'username' => $post->user->username,
        ]);
        return $this->render($this->_theme . 'image', [
                'rightBarPosts' => $rightBarPosts,
                'prevPost' => $prevPost,
                'post' => $post,
                'album' => $album
            ]);
    }

    public function actionImageOLD($url)
    {
        $post = UserPost::find()->where(['url' => $url])->one();
        if (empty($post) && !empty($this->session['initial'])) {
            return $this->renderPhotoSingle($url);
        } elseif (empty($post)) {
            throw new \yii\web\NotFoundHttpException();
        }

        $post->viewCount += 1;
        $post->save();
        $album = isset($post->images[0]->album) ? $post->images[0]->album : false;

        \Yii::$app->params['seo']['title'] = \Yii::t('app', '{title} - Album on QRUTO', [
                'title' => $post->title,
            ]);

        \Yii::$app->params['seo']['keywords'] = \Yii::t('app', 'pictures, images, funny, image hosting, image sharing, awesome images, current events, cute, cool pictures');
        \Yii::$app->params['seo']['description'] = \Yii::t('app', 'Album with topic of {topic} uploaded by {username}. {title}.', [
                'topic' => \Yii::$app->params['topicValues'][$post->topic]['title'],
                'title' => $post->title,
                'username' => $post->user->username,
            ]);
        return $this->render($this->_theme . 'image', ['post' => $post, 'album' => $album]);
    }
    public function actionDownloadImage($id)
    {
        $image = UserImage::findOne($id);
        if (empty($image))
            throw new \yii\web\NotFoundHttpException();

        $zip = new \ZipArchive(); // подгружаем библиотеку zip
        $archiveName = realpath(__DIR__ . '/../runtime') . "/album" . time() . ".zip"; // имя файла
        $zip->open($archiveName, \ZIPARCHIVE::CREATE);

        copy($image->original, realpath(__DIR__ . '/../runtime') . "/$image->name");
        $zip->addFile(realpath(__DIR__ . '/../runtime') . "/$image->name", $image->name); // добавляем файлы в zip архив

        $zip->close();
        unlink(realpath(__DIR__ . '/../runtime') . "/$image->name");
        // отдаём файл на скачивание
        header('Content-type: application/zip');
        header('Content-Disposition: attachment; filename="' . $image->name . '.zip"');
        readfile($archiveName);
        // удаляем zip файл если он существует
        unlink($archiveName);
        Yii::$app->end();
    }

    public function actionDownloadAlbum($id)
    {
        $album = UserAlbum::findOne($id);
        if (empty($album))
            throw new \yii\web\NotFoundHttpException();

        $zip = new \ZipArchive(); // подгружаем библиотеку zip
        $archiveName = realpath(__DIR__ . '/../runtime') . "/album" . time() . ".zip"; // имя файла
        $zip->open($archiveName, \ZIPARCHIVE::CREATE);
        foreach ($album->images as $image) {
            copy($image->original, realpath(__DIR__ . '/../runtime') . "/$image->name");
            $zip->addFile(
                realpath(__DIR__ . '/../runtime') . "/$image->name",
                $image->name
            ); // добавляем файлы в zip архив

        }
        $zip->close();
        foreach ($album->images as $image) {
            unlink(realpath(__DIR__ . '/../runtime') . "/$image->name");
        }
        // отдаём файл на скачивание
        header('Content-type: application/zip');
        header('Content-Disposition: attachment; filename="' . $album->title . '.zip"');
        readfile($archiveName);
        // удаляем zip файл если он существует
        unlink($archiveName);
        Yii::$app->end();
    }

    public function actionRemoveAlbum($id)
    {
        \Yii::$app->params['seo']['title'] = \Yii::t('app', 'Delete images - QRUTO');
        $album = UserAlbum::findOne($id);
        if (empty($album))
            throw new \yii\web\NotFoundHttpException();
        if (!empty($_POST['confirm'])) {
            $post = false;
            foreach ($album->images as $image) {
                if (!empty($image->post)) {
                    $post = $image->post;
                }
                UserImage::remove($image);
            }
            $album->delete();
            if (!empty($post)) {
                $post->delete();
            }
            return $this->render('upload/removeAlbumConfirm');
        }
        return $this->render('upload/removeAlbum', ['album' => $album]);
    }

    public function actionRemoveImageAnon($url)
    {
        \Yii::$app->params['seo']['title'] = \Yii::t('app', 'Delete images - QRUTO');
        $image = UserImage::find()
            ->where(['url' => $url])
            ->andWhere(['active' => 0])
            ->andWhere(['userId' => Yii::$app->params['defaultUser']['id']])
            ->one();
        if (empty($image))
            throw new \yii\web\NotFoundHttpException();

        UserImage::remove($image);
        $this->session->remove('initial');
        $this->session->remove('images');
        return $this->render('upload/removeImageConfirm', []);
    }

    public function actionRemoveImage($id)
    {
        \Yii::$app->params['seo']['title'] = \Yii::t('app', 'Delete images - QRUTO');
        $image = UserImage::findOne($id);
        if (empty($image))
            throw new \yii\web\NotFoundHttpException();

        if (!empty($_POST['confirm'])) {
            UserImage::remove($image);
            $this->session->remove('initial');
            $this->session->remove('images');
            return $this->render('upload/removeImageConfirm', []);
        }

        return $this->render('upload/removeImage', ['image' => $image]);
    }

    public function actionEdit()
    {
        $images = $this->session->get('images');
        $images = UserImage::find()->where(['url' => $images])->orderBy('sort asc')->all();
        $album = UserAlbum::find()->where(['id' => $this->session['initial']['albumId']])->one();
        return $this->render('upload/edit', ['images' => $images, 'album' => $album]);
    }

    public function actionBrowse()
    {
        $images = $this->session->get('images');
        $images = UserImage::find()->where(['url' => $images])->orderBy('sort asc')->all();
        $album = UserAlbum::find()->where(['id' => $this->session['initial']['albumId']])->one();
        return $this->render('upload/browse', ['images' => $images, 'album' => $album]);
    }

    public function actionRearrange()
    {
        if (!empty($_POST['item'])) {
            foreach ($_POST['item'] as $key => $item) {
                //echo "$key => $item <br />";
                $model = UserImage::findOne($item);
                $model->sort = $key + 1;
                $model->save();
            }
            echo \yii\helpers\BaseJson::encode([]);
            Yii::$app->end();
        }
        $images = $this->session->get('images');
        $images = UserImage::find()->where(['url' => $images])->orderBy('sort asc')->all();
        $album = UserAlbum::find()->where(['id' => $this->session['initial']['albumId']])->one();
        return $this->render('upload/rearrange', ['images' => $images, 'album' => $album]);
    }

    public function actionChangealbum()
    {
        $model = UserAlbum::find()->where(['id' => $_POST['UserAlbum']['id']])->one();
        if (empty($model))
            throw new \yii\web\NotFoundHttpException();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            echo \yii\helpers\BaseJson::encode([]);
            Yii::$app->end();
        }
    }

    public function actionChangeimage()
    {
        unset($_POST['_csrf']);
        foreach ($_POST as $post) {
            $model = UserImage::find()->where(['id' => $post['UserImage']['id']])->one();
            if (empty($model))
                throw new \yii\web\NotFoundHttpException();

            if ($model->load($post) && $model->validate()) {
                $model->save();
            }
        }

        if (!empty($model->album)) {
            $url = 'a/' . $model->album->url;
        } else {
            $url = $model->url;
        }

        echo \yii\helpers\BaseJson::encode(['url' => $url]);
        Yii::$app->end();
    }

    private function renderPhotoSingle($url)
    {
        $image = UserImage::find()->where(['url' => $url])->one();
        if (empty($image))
            throw new \yii\web\NotFoundHttpException();

        $initial = $this->session->get('initial');

        return $this->render('upload/' . $this->_theme . 'uploadSingle', ['image' => $image, 'initial' => $initial]);
    }

    /**
     * Загрузка картинки.
     *
     * @return string
     */
    public function actionUpload()
    {
        if (!empty($_POST['initial'])) {
            $this->session->remove('initial');
            $this->session->remove('images');
            $type = 'image';
            $albumUrl = false;
            if (!empty($_POST['album'])) {
                $album = new UserAlbum();
                $album->userId = (\Yii::$app->user->isGuest) ? Yii::$app->params['defaultUser']['id'] : $this->user->id;
                $album->url = UserAlbum::generateUrl();
                $album->language = Yii::$app->language;
                $album->save();
                $album->title = $_POST['album'] . $album->id;
                $album->save();
                $type = 'album';
                $albumUrl = $album->url;
            }
            $this->session->set(
                'initial',
                [
                    '_csrf' => $_POST['_csrf'],
                    'albumId' => !empty($album->id) ? $album->id : null,
                    'publish' => !empty($_POST['publish']) ? true : false,
                ]
            );
            echo \yii\helpers\BaseJson::encode(['type' => $type, 'albumUrl' => $albumUrl]);
            Yii::$app->end();
        } else {
            $is = @getimagesize($_FILES['image']['tmp_name']);
            if (!$is)
                Yii::$app->end();

            $name = UserImage::generateUrl();
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

            $original = UserImage::saveImage($_FILES['image']['tmp_name'], 'original', $name . '.' . $extension);

            if (!empty($this->session['images'])) {
                $this->session->set('images', array_merge($this->session['images'], [$name]));
            } else {
                $this->session->set('images', [$name]);
            }
            $thumb160 = UserImage::saveThumbnail($_FILES['image']['tmp_name'], 'thumb160', $name . '160.' . $extension);
            $thumb90 = UserImage::saveThumbnail($_FILES['image']['tmp_name'], 'thumb90', $name . '90.' . $extension);

            $model = new UserImage();
            $model->userId = (\Yii::$app->user->isGuest) ? Yii::$app->params['defaultUser']['id'] : $this->user->id;
            $model->url = $name;
            $model->language = Yii::$app->language;
            $model->name = $_FILES['image']['name'];
            $model->original = $original;
            $model->thumb160 = $thumb160;
            $model->thumb90 = $thumb90;
            $model->save();

            $model->user->imageCount += 1;
            if (!empty($this->session['initial']['albumId'])) {
                $albumImage = new AlbumImage();
                $albumImage->albumId = $this->session['initial']['albumId'];
                $albumImage->imageId = $model->id;
                $albumImage->save();
            }

            echo \yii\helpers\BaseJson::encode(['imageUrl' => $model->url]);
            Yii::$app->end();
        }
    }
    
    /**
     * Загрузка картинки.
     *
     * @return string
     */
    public function actionUploadMobile()
    {
        if (!empty($_POST['initial'])) {
            $this->session->remove('initial');
            $this->session->remove('images');
            $type = 'image';
            $albumUrl = false;
            $createAlbum = (int)$_POST['album'];
            $createPost = (int)$_POST['publish'];

            if (!empty($createAlbum)) {
                $album = new UserAlbum();
                $album->userId = (\Yii::$app->user->isGuest) ? Yii::$app->params['defaultUser']['id'] : $this->user->id;
                $album->url = UserAlbum::generateUrl();
                $album->language = Yii::$app->language;
                $album->title = $_POST['UserAlbum']['title'];
                $album->description = $_POST['UserAlbum']['description'];
                $album->save(false);

                $type = 'album';
                $albumUrl = $album->url;
            }

            if (!empty($createPost)) {
                $postModel = new UserPost();
                $postModel->userId = $this->user->id;
                $postModel->url = UserAlbum::generateUrl();
                $postModel->active = true;
                $postModel->mature = !empty($_POST['mature']) ? 1 : 0;
                $postModel->language = \Yii::$app->language;
                if ($postModel->load($_POST) && $postModel->validate()) {
                    $postModel->save();
                } else {
                    echo \yii\helpers\BaseJson::encode(array_merge($postModel->getErrors(), ['errors' => true]));
                    Yii::$app->end();
                }
                $type = 'post';
                $albumUrl = $postModel->url;
            }

            $this->session->set(
                'initial',
                [
                    '_csrf' => $_POST['_csrf'],
                    'albumId' => !empty($album->id) ? $album->id : null,
                    'postId' => !empty($postModel->id) ? $postModel->id : null,
                    'publish' => !empty($_POST['publish']) ? true : false,
                ]
            );
            echo \yii\helpers\BaseJson::encode(['type' => $type, 'albumUrl' => $albumUrl]);
            Yii::$app->end();
        } else {
            $is = @getimagesize($_FILES['image']['tmp_name']);

            if (!$is)
                Yii::$app->end();

            $name = UserImage::generateUrl();
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

            $original = UserImage::saveImage($_FILES['image']['tmp_name'], 'original', $name . '.' . $extension);

            if (!empty($this->session['images'])) {
                $this->session->set('images', array_merge($this->session['images'], [$name]));
            } else {
                $this->session->set('images', [$name]);
            }
            $thumb160 = UserImage::saveThumbnail($_FILES['image']['tmp_name'], 'thumb160', $name . '160.' . $extension);
            $thumb90 = UserImage::saveThumbnail($_FILES['image']['tmp_name'], 'thumb90', $name . '90.' . $extension);

            $model = new UserImage();
            $model->userId = (\Yii::$app->user->isGuest) ? Yii::$app->params['defaultUser']['id'] : $this->user->id;
            $model->url = $name;
            $model->language = Yii::$app->language;
            $model->name = $_FILES['image']['name'];
            $model->original = $original;
            $model->thumb160 = $thumb160;
            $model->thumb90 = $thumb90;
            $model->save();

            $model->user->imageCount += 1;
            if (!empty($this->session['initial']['albumId'])) {
                $albumImage = new AlbumImage();
                $albumImage->albumId = $this->session['initial']['albumId'];
                $albumImage->imageId = $model->id;
                $albumImage->save();
            }

            if (!empty($this->session['initial']['postId'])) {
                $imageRelation = new PostImage();
                $imageRelation->postId = $this->session['initial']['postId'];
                $imageRelation->imageId = $model->id;
                $imageRelation->save();
            }

            echo \yii\helpers\BaseJson::encode(['imageUrl' => $model->url]);
            Yii::$app->end();
        }
    }

    /**
     * Востановление пароля.
     *
     * @return string
     */
    public function actionPassword()
    {
        \Yii::$app->params['seo']['title'] = \Yii::t('app', 'Forgot your password? - QRUTO');
        $model = new PasswordRecovery(['scenario' => PasswordRecovery::SCENARIO_CODE]);
        if ($model->load(Yii::$app->request->post()) && $model->recover()) {
            $authKey = uniqid();
            $model->_user->authKey = $authKey;
            $model->_user->save();

            $mailer = new \PHPMailer();
            $mailer->setFrom(Yii::$app->params['adminEmail']);
            $mailer->addAddress($model->_user->email);
            $mailer->isHTML(true);

            $mailer->Subject = \yii\helpers\Url::base();
            $mailer->Body = $this->renderPartial(
                'emailTemplates/recoveryMail',
                [
                    'authKey' => $authKey,
                ]
            );
            $mailer->AltBody = $authKey;
            if (!$mailer->send()) {
                throw new Exception('Mailer Error: ' . $mailer->ErrorInfo);
            }
            $template = $this->renderPartial('password2', []);
            echo \yii\helpers\BaseJson::encode(['success' => $template]);
            Yii::$app->end();
        } elseif ($model->getErrors()) {
            echo \yii\helpers\BaseJson::encode($model->getErrors());
            Yii::$app->end();
        }

        return $this->render('password', []);
    }

    /**
     * Востановление пароля.
     *
     * @return string
     */
    public function actionChangepassword($code)
    {
        $user = User::find()->where(['authKey' => $code])->one();
        if (empty($user))
            throw new \yii\web\NotFoundHttpException();

        $model = new PasswordRecovery(['scenario' => PasswordRecovery::SCENARIO_RECOVERY]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user->authKey = null;
            $user->password = md5($model->password);
            $user->save();
            $template = $this->renderPartial('changepasswordcomplete', []);
            echo \yii\helpers\BaseJson::encode(['success' => $template]);
            Yii::$app->end();
        } elseif ($model->getErrors()) {
            echo \yii\helpers\BaseJson::encode($model->getErrors());
            Yii::$app->end();
        }
        return $this->render('changepassword', []);
    }

    /**
     * Востановление пароля.
     *
     * @return string
     */
    public function actionPassword2()
    {
        return $this->render('password2', []);
    }

    public function actionRules()
    {
        \Yii::$app->params['seo']['title'] = \Yii::t('app', 'QRUTO: Community Rules');
        \Yii::$app->params['seo']['description'] = \Yii::t('app', 'Rules for posting images and comments.');
        return $this->render('rules', []);
    }

    public function actionAuth()
    {
        return $this->render($this->_theme . 'auth', []);
    }

    public function actionRegistration()
    {
        return $this->render($this->_theme . 'registration', []);
    }
    
    public function actionImageUpload()
    {
        return $this->render($this->_theme . 'image_upload', []);
    }
 /*
    public function actionTest()
    {
        ini_set("memory_limit","2048M");
        set_time_limit(0);

        require_once realpath(__DIR__ . '/../PHPExcel/Classes/PHPExcel/IOFactory.php');

        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');

//        $categories = [
//            'reaction' => 4,
//            'stories' => 2,
//            'new knowledge' => 1,
//            'Science' => 9,
//            'amazing' => 0,
//            'Other' => 5,
//            'funny' => 7,
//            'art and design' => 8,
//            'Current Events' => 3,
//            'inspiring' => 6,
//        ];
        $categories = [
            'Reaction' => 4,
            'Stories' => 2,
            'new knowledge' => 1,
            'The science' => 9,
            'amazing' => 0,
            'Other' => 5,
            'funny' => 7,
            'Design and Art' => 8,
            'Current Events' => 3,
            'Inspiration' => 6,
        ];
        $find = \yii\helpers\FileHelper::findFiles(realpath(__DIR__ . '/../rus'));
        $importData = $this->filterByDirectory($find);

        foreach ($importData as $category => $files) {
            // есть категория перебераем файлы
            if (isset($categories[$category]) && $category == 'Inspiration') {
                $objPHPExcel = $objReader->load($files['xls']);
                $sheetData = $objPHPExcel->getActiveSheet()->toArray();
                $ImageData = [];
                foreach ($sheetData as $rowData) {
                    if (empty($rowData[1]))
                        continue;

                    $ImageData[$rowData[1]] = $rowData[2];
                }
                unset($files['xls']);
                foreach ($files as $file) {
                    $name = basename($file);
                    if (!empty($ImageData[$name])) {
                        $title = $ImageData[$name];
                        $this->sendPost($file, $title, $categories[$category]);
                    }

                }
//                var_dump($ImageData[$name]); exit;
            }
        }
//        var_dump($importData); exit;
    }

    private function sendPost($file, $title, $category)
    {
        $name = UserImage::generateUrl();
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        $original = UserImage::saveImage($file, 'original', $name . '.' . $extension);

        $thumb160 = UserImage::saveThumbnail($file, 'thumb160', $name . '160.' . $extension);

        $thumb90 = UserImage::saveThumbnail($file, 'thumb90', $name . '90.' . $extension);

        $model = new UserImage();
        $model->userId = 1;
        $model->url = $name;
        $model->language = 'ru';
        $model->name = basename($file);
        $model->original = $original;
        $model->thumb160 = $thumb160;
        $model->thumb90 = $thumb90;
        $model->save();

        $postModel = new UserPost();
        $postModel->userId = 1;
        $postModel->title = $title;
        $postModel->topic = $category;
        $postModel->url = UserAlbum::generateUrl();
        $postModel->language = 'ru';
        $postModel->mature = 0;
        $postModel->active = true;
        $postModel->save();

        $imageRelation = new PostImage();
        $imageRelation->postId = $postModel->id;
        $imageRelation->imageId = $model->id;
        $imageRelation->save();
    }

    private function filterByDirectory($array)
    {
        $directories = [];
        foreach ($array as $item) {
            $pies = explode('/', $item);
            if (empty($directories[$pies[8]])) {
                $directories[$pies[8]][] = $item;
            } else {
                if (strpos($item, 'xlsx')) {
                    $directories[$pies[8]]['xls'] = $item;
                } else {
                    array_push($directories[$pies[8]], $item);
                }
            }

        }
        return $directories;
    }
    */
}
