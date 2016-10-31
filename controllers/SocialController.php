<?php

namespace app\controllers;

use app\models\User;
use Yii;
use Facebook;
use \BW\Vkontakte as Vk;

//$yahoo = Yii::getAlias('@app/vendor/yahoo/Yahoo.inc');
$weibo = Yii::getAlias('@app/vendor/xiaosier/libweibo/saetv2.ex.class.php');
$oAuth = Yii::getAlias('@app/vendor/yahoo5/OAuth/OAuth.php');
$yahoo = Yii::getAlias('@app/vendor/yahoo5/Yahoo/YahooOAuthApplication.class.php');

require_once($oAuth);
require_once($yahoo);
require_once($weibo);
class SocialController extends AbstractController
{
    public $facebook;

    public $yahoo;

    public $vk;

    public $google;

    public $weibo;

    public function init()
    {
        parent::init();

        $this->facebook = new \Facebook\Facebook([
            'app_id' => Yii::$app->params['social']['facebook']['id'],
            'app_secret' => Yii::$app->params['social']['facebook']['secret'],
        ]);

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
    }

    public function actionVk()
    {
        if (isset($_GET['code'])) {
            $this->vk->authenticate();

            $user = $this->vk->api('users.get', [
                    'user_id' => $this->vk->getUserId(),
                    'fields' => [
                        'nickname',
                        'photo_50',
                        'city',
                        'sex',
                    ],
                ]);

            $this->auth([
                    'id' => (string) $user[0]['id'],
                    'name' => !empty($user[0]['nickname']) ? $user[0]['nickname'] : $user[0]['first_name'],
                    'email' => null,
                ], 'vk');
        }
    }

    public function actionYahoo()
    {
        $request_token = new \YahooOAuthRequestToken($_REQUEST['openid_oauth_request_token'], '');

        $this->yahoo->token = $this->yahoo->getAccessToken($request_token);

        $profile = $this->yahoo->getProfile();

        $this->auth([
                'id' => $profile->profile->guid,
                'name' => $profile->profile->nickname,
                'email' => null,
            ], 'yahoo');
    }

    public function actionFacebook()
    {
        $helper = $this->facebook->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (! isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        $response = $this->facebook->get('/me?fields=id,name,email', $accessToken->getValue());
        $user = $response->getGraphUser();
        $this->auth([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ], 'facebook');
    }

    public function actionGoogle()
    {
        $this->google = new \Google_Client();
        $this->google->setApplicationName('Qruto');
        $this->google->setScopes(array('https://www.googleapis.com/auth/plus.me'));
        $this->google->setClientId(Yii::$app->params['social']['google']['id']);
        $this->google->setClientSecret(Yii::$app->params['social']['google']['apiKey']);
        $this->google->setRedirectUri('http://' . Yii::$app->getRequest()->serverName . '/social/google');

        $plus = new \Google_Service_Plus($this->google);

        $this->google->authenticate($_GET['code']);

        $user = $plus->people->get('me');

        $this->auth([
                'id' => $user['id'],
                'name' => !empty($user['displayName']) ? $user['displayName'] : 'u' . $user['id'],
                'email' => '',
            ], 'google');
    }

    public function actionWeibo()
    {
        if (isset($_REQUEST['code'])) {
            $this->weibo = new \SaeTOAuthV2(Yii::$app->params['social']['weibo']['ClientID'] , Yii::$app->params['social']['weibo']['ClientSecret']);

            $keys = array();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = 'http://' . Yii::$app->getRequest()->serverName . '/social/weibo';
            $token = $this->weibo->getAccessToken('code', $keys) ;

            $client = new \SaeTClientV2(Yii::$app->params['social']['weibo']['ClientID'] , Yii::$app->params['social']['weibo']['ClientSecret'] , $token['access_token']);

            $id = $client->get_uid();
            $user = $client->show_user_by_id($id['uid']);


            $this->auth([
                    'id' => $user['id'],
                    'name' => $user["name"],
                    'email' => '',
                ], 'weibo');
        }
    }

    private function auth($params, $method)
    {
        $user = User::find()
            ->where(['authID' => $params['id']])
            ->andWhere(['authMethod' => $method])
            ->one();

        if (empty($user)) {
            $user = new User();
            $user->username = $params['name'];
            $user->email = $params['email'];
            $user->authID = (string) $params['id'];
            $user->authMethod = $method;

            if (!$user->validate()) {
                var_dump($user->getErrors());
                exit;
            } else {
                $user->save();
            }
        }

        Yii::$app->user->login($user, true ? 3600*24*30 : 0);
        return $this->goHome();
    }
}
