<?php

return [
    'adminEmail' => 'admin@example.com',
    'defaultUser' => [
        'id' => 1,
        'name' => 'qruto'
    ],
    'languages' => [
        'en' => [
            'name' => 'English',
            'image' => '/images/usa.png'
        ],
        'ru' => [
            'name' => 'Русский',
            'image' => '/images/rus.png'
        ],
    ],
    'version' => [
       'mobile' => [
           'host' => 'http://m.qruto.com',
           'domain' => 'm.qruto.com'
       ],
        'full' =>[
            'host' => 'http://qruto.com',
            'domain' => 'qruto.com'
        ]
    ],
	'imageUploadFolder' => 'uploads',
    'imageDefaultServer' => 'http://cdn1.qruto.com',
	'imagePerPageMain' => 80,
    'imageRightBar' => 15,
    'seo' => [
        'title' => \Yii::t('app', 'QRUTO: The most amazing images in the Internet'),
        'keywords' => \Yii::t('app', 'pictures, images, funny, image hosting, image sharing, awesome images, current events, cute, cool pictures'),
        'description' => \Yii::t('app', 'QRUTO is the most interesting resource to share the most popular images.'),
    ],
    'social' => [
        'twitter' => [
            'twitterKey' => '',
            'twitterSecret' => '',
        ],
        'vk' => [
            'id' => '5502193',
            'secret' => 'sdtJaR3vwe2YHdTedIA4',
        ],
        'google' => [
            'apiKey' => 'HvWQGOnpB7xDPPOjlYITLt41',
            'id' => '958948178208-ggv307dn1r0c3v3u0ublrnsma0p5chat.apps.googleusercontent.com',
        ],
        'weibo' => [
            'ClientID' => '35147913',
            'ClientSecret' => '33154ec4b10e31dcc841c294823e44f9',
        ],
        'facebook' => [
            'id' => '527659790751462',
            'secret' => 'f27475e7bf861149ff8a8a2e567cd207',
        ],
        'yahoo' => [
            'applicationId' => 'vleaNF7a',
            'consumerKey' => 'dj0yJmk9NVg2b1dhN2NYWHRUJmQ9WVdrOWRteGxZVTVHTjJFbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmeD01Yw--',
            'consumerSecret' => '302ff04299fa3fc3a886f32ae89c87b5cd1b8756',
        ],
    ],
    'topicValues' => [
        0 => [
            'title' => \Yii::t('app', 'Amazing'),
            'description' => \Yii::t('app', 'Awesome and cool things.')
        ],
        1 => [
            'title' => \Yii::t('app', 'New knowledge'),
            'description' => \Yii::t('app', 'Interesting or mystery things.')
        ],
        2 => [
            'title' => \Yii::t('app', 'Stories'),
            'description' => \Yii::t('app', 'Ones upon a time....')
        ],
        3 => [
            'title' => \Yii::t('app', 'Current Events'),
            'description' => \Yii::t('app', 'What is happening now.')
        ],
        4 => [
            'title' => \Yii::t('app', 'Reaction'),
            'description' => \Yii::t('app', '\'My reaction when\' moments.')
        ],
        5 => [
            'title' => \Yii::t('app', 'Other'),
            'description' => \Yii::t('app', 'Posts that does not fit in the other sections.')
        ],
        6 => [
            'title' => \Yii::t('app', 'Inspiring'),
            'description' => \Yii::t('app', 'Something that inspires.')
        ],
        7 => [
            'title' => \Yii::t('app', 'Funny'),
            'description' => \Yii::t('app', 'Share the fun with others.')
        ],
        8 => [
            'title' => \Yii::t('app', 'Art and Design'),
            'description' => \Yii::t('app', 'Creative and impressive things.')
        ],
        9 => [
            'title' => \Yii::t('app', 'Science'),
            'description' => \Yii::t('app', 'Nature and technology.')
        ],
    ]
];
