<?php

namespace app\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @return string название таблицы, сопоставленной с этим ActiveRecord-классом.
     */
    public static function tableName()
    {
        return 'user';
    }

/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['authMethod'], 'string'],
            [['likeCount', 'imageCount', 'commentCount', 'mature', 'notification', 'promotional'], 'integer'],
            [['username', 'email', 'password', 'authKey', 'accessToken', 'authID'], 'string', 'max' => 255],
            [['about'], 'string', 'max' => 1024],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'date' => 'Date',
            'authKey' => 'Auth Key',
            'accessToken' => 'Access Token',
            'authMethod' => 'Auth Method',
            'authID' => 'Auth ID',
            'about' => 'About',
            'likeCount' => 'Like Count',
            'imageCount' => 'Image Count',
            'commentCount' => 'Comment Count',
            'mature' => 'Mature',
            'notification' => 'Notification',
            'promotional' => 'Promotional',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAlbums()
    {
        return $this->hasMany(UserAlbum::className(), ['userId' => 'id'])->orderBy(['sort' => SORT_ASC, 'date' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentsBest()
    {
        return $this->hasMany(UserComment::className(), ['userId' => 'id'])->orderBy(['status' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentsWorst()
    {
        return $this->hasMany(UserComment::className(), ['userId' => 'id'])->orderBy(['status' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentsAsc()
    {
        return $this->hasMany(UserComment::className(), ['userId' => 'id'])->orderBy(['date' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(UserComment::className(), ['userId' => 'id'])->orderBy(['date' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFavorites()
    {
        return $this->hasMany(UserFavorite::className(), ['userId' => 'id'])->asArray();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFavoritesDesc()
    {
        return $this->hasMany(UserPost::className(), ['id' => 'postId'])
            ->viaTable('user_favorite', ['userId' => 'id'])
            ->orderBy(['date' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFavoritesAsc()
    {
        return $this->hasMany(UserPost::className(), ['id' => 'postId'])
            ->viaTable('user_favorite', ['userId' => 'id'])
            ->orderBy(['date' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFavoritesBest()
    {
        return $this->hasMany(UserPost::className(), ['id' => 'postId'])
            ->viaTable('user_favorite', ['userId' => 'id'])
            ->orderBy(['likeCount' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFavoritesWorst()
    {
        return $this->hasMany(UserPost::className(), ['id' => 'postId'])
            ->viaTable('user_favorite', ['userId' => 'id'])
            ->orderBy(['likeCount' => SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImages()
    {
        return $this->hasMany(UserImage::className(), ['userId' => 'id']);
    }

    public function getNotReadReplies()
    {
        return $this->hasMany(UserComment::className(), ['userId' => 'id'])
            ->andOnCondition(
                'JOIN comment_reply ON comment_reply.commentId = comment.id AND comment_reply.readOwner = 0'
                , [])
            //->andWhere('comment_reply1.readOwner = :readOwner', [':readOwner' => 0])
            ->orderBy(['date' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(UserPost::className(), ['userId' => 'id'])->orderBy(['date' => SORT_DESC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostsSort()
    {
        $sort = \Yii::$app->request->cookies->getValue('sort', 'date');
        $topic = \Yii::$app->request->cookies->getValue('sort_topic', 'all');
        $posts = $this->hasMany(UserPost::className(), ['userId' => 'id']);
        if (!empty($topic)) {
            $posts->andWhere('topic = :topic', [':topic' => $topic]);
        }
        switch ($sort) {
            case 'date':
                $posts->orderBy(['date' => SORT_DESC]);
                break;
            case 'rand':
                $posts->orderBy('RAND()');
                break;
            case 'view':
                $posts->orderBy(['viewCount' => SORT_DESC]);
                break;
            default:
                $posts->orderBy(['date' => SORT_DESC]);
                break;
        }
        return $posts->limit(10);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostsAsc()
    {
        return $this->hasMany(UserPost::className(), ['userId' => 'id'])->orderBy(['date' => SORT_ASC]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $user = self::findOne($id);
        return isset($user) ? $user : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
		$user = User::find()->where(['username' => $username])->one();
        if (!empty($user))
            return $user;

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === md5($password);
    }
	

    public static function changeDate ($date,$sex = 0)
    {
        list($day, $time) = explode(' ', $date);
        list($y, $m, $d) = explode('-', $day);
        list($h, $min, $s) = explode(':', $time);
        $cur_date = date('Y-m-d H:i:s');

        $cur_date_unix = strtotime($cur_date);
        $date_unix = strtotime($date);

        $result = $cur_date_unix - $date_unix;
        $sex = ($sex == 1) ? 'а' : '';

        if($result < 1) {
            return \Yii::t('app','just now');
        }

        if($result < 60*60)
        {
            $mins = "".round($result / 60, 0)."";
            if(strlen($mins) == 1) $mins = "0".$mins;

            if($mins <= 1) {
                $result = \Yii::t('app',"1 minute ago");
            } elseif($mins[1] >= 2 && $mins[1] < 5 && $mins[0] != 1) {
                $result = intval($mins).\Yii::t('app'," minutes ago");
            } elseif(($mins[1] >=5 && $mins[1] <= 9) or $mins[1] == 0 or $mins[0] == 1) {
                $result = intval($mins).\Yii::t('app'," minutes ago");
            } elseif($mins[1] == 1)	{
                $result = intval($mins).\Yii::t('app'," minute ago");
            } else {
                $result = intval($mins).\Yii::t('app'," minute ago");
            }

        }
        //elseif($result < 60*60*24 && $result >= 60*60 && $day == date('Y-m-d',mktime(0,0,0,date("m"),date("d"),date("Y"))))
        elseif($result < 60*60*24 && $result >= 60*60)
        {
            //$hours = round($result / 1440, 0);
            $hours = floor($result / 3600);
            $hours = (int) $hours;
            if($hours <= 1 || $hours == 21) {
                $result = $hours.\Yii::t('app'," hour ago");
            } elseif( ($hours >= 2 && $hours < 5) || $hours == 22 || $hours >= 23) {
                $result = $hours.\Yii::t('app'," hours ago");
            } elseif($hours >= 5 && $hours < 21) {
                $result = $hours.\Yii::t('app'," hours ago");
            } else {
                $result = $hours.\Yii::t('app'," hours ago");
            }

            //$result = $h.':'.$min; // 14:31
        }
        elseif($day == date('Y-m-d',mktime(0,0,0,date("m"),date("d")-1,date("Y"))))
        {
            $result = \Yii::t('app', 'yesterday').' '.\Yii::t('app', 'in').' '.$h.':'.$min; // вчера в 16:46
        }
        else
        {
            list($y2, $m2, $d2) = explode('-', $cur_date);
            if($y == $y2)
            {
                $month_str = array(
                    \Yii::t('app', 'January'),
                    \Yii::t('app', 'February'),
                    \Yii::t('app', 'March'),
                        \Yii::t('app', 'April'),
                            \Yii::t('app', 'May'),
                                \Yii::t('app', 'June'),
                                    \Yii::t('app', 'July'),
                                        \Yii::t('app', 'August'),
                                            \Yii::t('app', 'September'),
                                                \Yii::t('app', 'October'),
                                                    \Yii::t('app', 'November'),
                                                        \Yii::t('app', 'December')
                );
                $month_int = array(
                    '01', '02', '03',
                    '04', '05', '06',
                    '07', '08', '09',
                    '10', '11', '12'
                );
                $m = str_replace($month_int, $month_str, $m);
                $result = intval($d).' '.\Yii::t('app', $m).' '.\Yii::t('app', 'in').' '.$h.':'.$min; // 20 октября
            }
            else
            {
                $result = $d.'.'.$m.'.'.$y.' '.\Yii::t('app', 'in').' '.$h.':'.$min; //12.11.2011
            }
        }
        return $result;
    }
}
