<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_post".
 *
 * @property integer $id
 * @property integer $userId
 * @property string $title
 * @property integer $topic
 * @property string $date
 *
 * @property PostImage[] $postImages
 * @property PostTags[] $postTags
 * @property User $user
 */
class UserPost extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'title', 'topic'], 'required', 'message' => \Yii::t('app', 'Field can not be empty.')],
            [['userId', 'topic'], 'integer'],
            [['date'], 'safe'],
            [['title'], 'string', 'max' => 255],
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
            'title' => 'Title',
            'topic' => 'Topic',
            'date' => 'Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostImages()
    {
        return $this->hasMany(PostImage::className(), ['postId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostTags()
    {
        return $this->hasMany(PostTags::className(), ['postId' => 'id']);
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
    public function getImages()
    {
        return $this->hasMany(UserImage::className(), ['id' => 'imageId'])
            ->viaTable('post_image', ['postId' => 'id'])
            ->orderBy(['sort' => SORT_ASC]);
    }	
	    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tags::className(), ['id' => 'tagId'])->viaTable('post_tags', ['postId' => 'id']);
    }

	/**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentsBest()
    {
        return $this->hasMany(UserComment::className(), ['postId' => 'id'])->orderBy(['status' => SORT_DESC]);
    }

	/**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentsWorst()
    {
        return $this->hasMany(UserComment::className(), ['postId' => 'id'])->orderBy(['status' => SORT_ASC]);
    }

	/**
     * @return \yii\db\ActiveQuery
     */
    public function getCommentsAsc()
    {
        return $this->hasMany(UserComment::className(), ['postId' => 'id'])->orderBy(['date' => SORT_ASC]);
    }

	/**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(UserComment::className(), ['postId' => 'id'])->orderBy(['date' => SORT_DESC]);
    }

    public function getNext() {
        $next = $this->find()->where(['>', 'id', $this->id])->one();
        return $next;
    }

    public function getPrev() {
        $prev = $this->find()->where(['<', 'id', $this->id])->orderBy('id desc')->one();
        return $prev;
    }
}