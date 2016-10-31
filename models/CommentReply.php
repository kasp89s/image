<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comment_reply".
 *
 * @property integer $id
 * @property integer $commentId
 * @property integer $userId
 * @property string $message
 * @property string $date
 * @property integer $status
 *
 * @property UserComment $comment
 */
class CommentReply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment_reply';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['commentId', 'userId', 'message'], 'required'],
            [['commentId', 'userId', 'status'], 'integer'],
            [['message'], 'string'],
            [['date'], 'safe'],
            [['commentId'], 'exist', 'skipOnError' => true, 'targetClass' => UserComment::className(), 'targetAttribute' => ['commentId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'commentId' => 'Comment ID',
            'userId' => 'user ID',
            'message' => 'message',
            'date' => 'Date',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComment()
    {
        return $this->hasOne(UserComment::className(), ['id' => 'commentId']);
    }
	
	    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}