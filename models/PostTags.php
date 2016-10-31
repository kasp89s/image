<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "post_tags".
 *
 * @property integer $postId
 * @property integer $tagId
 *
 * @property Tags $tag
 * @property UserPost $post
 */
class PostTags extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_tags';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['postId', 'tagId'], 'required'],
            [['postId', 'tagId'], 'integer'],
            [['tagId'], 'exist', 'skipOnError' => true, 'targetClass' => Tags::className(), 'targetAttribute' => ['tagId' => 'id']],
            [['postId'], 'exist', 'skipOnError' => true, 'targetClass' => UserPost::className(), 'targetAttribute' => ['postId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'postId' => 'Post ID',
            'tagId' => 'Tag ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(Tags::className(), ['id' => 'tagId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(UserPost::className(), ['id' => 'postId']);
    }
}