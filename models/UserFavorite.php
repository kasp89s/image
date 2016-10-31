<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_favorite".
 *
 * @property integer $userId
 * @property integer $postId
 *
 * @property UserImage $image
 * @property User $user
 */
class UserFavorite extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_favorite';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'postId'], 'required'],
            [['userId', 'postId'], 'integer'],
            [['postId'], 'exist', 'skipOnError' => true, 'targetClass' => UserPost::className(), 'targetAttribute' => ['postId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => 'User ID',
            'postId' => 'postId ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(UserPost::className(), ['id' => 'postId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}