<h1>Hello, it's recovery mail template</h1>
<h3>You can change me in /views/site/emailTemplate/recoveryMail.php</h3>
Your recovery link -> <?= Yii::$app->getRequest()->serverName . '/changepassword/' . $authKey?> <br />
