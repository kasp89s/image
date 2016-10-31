<?php
$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
	'defaultRoute' => 'site/index',
    'language' => 'en', // <- здесь!
    'vendorPath' => realpath(__DIR__ . '/../vendor'),
    'components' => [
		'assetManager' => [
			'bundles' => [
				'yii\web\JqueryAsset' => [
					'sourcePath' => null,   // не опубликовывать комплект
					'js' => [
					]
				],
			],
		],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app'       => 'app.php',
                    ],
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'g65uerden',
        ],

		'urlManager' => [
               'enablePrettyUrl' => true,
			   'enableStrictParsing' => true,
               'showScriptName' => false,
			   'rules' => [
                    'list/<id:\d+>' => 'site/list',
				   	'changepassword/<code:\\w+>' => 'site/changepassword',
				   	'r/<url:\\w+>' => 'site/remove-image-anon',
                    'a/<url:\\w+>' => 'a/index',
                    'i/<url:\\w+>' => 'a/image',
                    'user/<username:\\w+>/' => 'profile/gallery',
                    'user/<username:\\w+>/comments' => 'profile/comments',
                    'user/<username:\\w+>/favorites' => 'profile/favorites',
                    'user/<username:\\w+>/replies' => 'profile/replies',
					'<controller>/<action>' => '<controller>/<action>',
					'<controller>/<action>/<id:\d+>' => '<controller>/<action>',
					'user' => 'user/login',
					'search' => 'site/search',
					'rules' => 'site/rules',
					'favorites' => 'site/favorites',
					'auth' => 'site/auth',
					'registration' => 'site/registration',
					'image-upload' => 'site/image-upload',
					'login' => 'site/login',
					'logout' => 'site/logout',
					'register' => 'site/register',
					'upload' => 'site/upload',
					'upload-mobile' => 'site/upload-mobile',
					'check' => 'site/check',
					'edit' => 'site/edit',
					'browse' => 'site/browse',
					'rearrange' => 'site/rearrange',
					'recovery' => 'site/password',
					'recoverysend' => 'site/password2',
					'/' => 'site/index',
					'<url:\\w+>' => 'site/image',
			    ]
		],

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];
	//http://qruto.com/index.php?r=gii
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '178.159.238.134', '94.153.218.229']
    ];
}

return $config;
