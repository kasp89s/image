<?php
namespace app\components;

class Twitter extends TwitterOAuth
{
    private $key;
    private $secret;

    public function __construct($oauth_token = null, $oauth_verifier = null)
    {
        $this->key = \Yii::$app->params['social']['twitter']['twitterKey'];
        $this->secret = \Yii::$app->params['social']['twitter']['twitterSecret'];
        parent::__construct($this->key, $this->secret, $oauth_token, $oauth_verifier);
    }

    public function getAccessURL()
    {
        $request_token = $this->getRequestToken();
        return $this->getAuthorizeURL($request_token);
    }

    public function getUser()
    {
        return $this->get('account/verify_credentials');
    }
}
