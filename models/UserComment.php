<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_comment".
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $imageId
 * @property string $message
 * @property string $date
 * @property integer $status
 *
 * @property CommentReply[] $commentReplies
 * @property User $user
 */
class UserComment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'postId', 'message'], 'required'],
            [['userId', 'postId', 'status'], 'integer'],
            [['message'], 'string'],
            [['date'], 'safe'],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'userId' => 'User ID',
            'postId' => 'image ID',
            'message' => 'message',
            'date' => 'Date',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReplies()
    {
        return $this->hasMany(CommentReply::className(), ['commentId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(UserPost::className(), ['id' => 'postId']);
    }
}